<?php

namespace Modus\Template;

use Aura\View\Template;
use Aura\View\TemplateFinder;
use Aura\View\HelperLocator;
use Aura\View\EscaperFactory;
use Aura\View\Helper;
use Aura\View\FormatTypes;

class Factory {
    
    protected $template;
    protected $layout;
    protected $viewPaths = array();

    public function __construct($layout, array $viewPaths = array()) {
        $this->layout = $layout;
        $this->viewPaths = $viewPaths;
        $this->init($layout, $viewPaths);
    }
    
    protected function init($layout, array $viewPaths = array()) {
        
        $input = [
            'button'         => function () { return new Helper\Form\Input\Generic; },
            'checkbox'       => function () { return new Helper\Form\Input\Checked; },
            'color'          => function () { return new Helper\Form\Input\Value; },
            'date'           => function () { return new Helper\Form\Input\Value; },
            'datetime'       => function () { return new Helper\Form\Input\Value; },
            'datetime-local' => function () { return new Helper\Form\Input\Value; },
            'email'          => function () { return new Helper\Form\Input\Value; },
            'file'           => function () { return new Helper\Form\Input\Generic; },
            'hidden'         => function () { return new Helper\Form\Input\Value; },
            'image'          => function () { return new Helper\Form\Input\Generic; },
            'month'          => function () { return new Helper\Form\Input\Value; },
            'number'         => function () { return new Helper\Form\Input\Value; },
            'password'       => function () { return new Helper\Form\Input\Value; },
            'radio'          => function () { return new Helper\Form\Input\Checked; },
            'range'          => function () { return new Helper\Form\Input\Value; },
            'reset'          => function () { return new Helper\Form\Input\Generic; },
            'search'         => function () { return new Helper\Form\Input\Value; },
            'submit'         => function () { return new Helper\Form\Input\Generic; },
            'tel'            => function () { return new Helper\Form\Input\Value; },
            'text'           => function () { return new Helper\Form\Input\Value; },
            'time'           => function () { return new Helper\Form\Input\Value; },
            'url'            => function () { return new Helper\Form\Input\Value; },
            'week'           => function () { return new Helper\Form\Input\Value; },
        ];


        $fields = array_merge($input, [
            'radios'     => function () { return new Helper\Form\Radios(new Helper\Form\Input\Checked); },
            'select'     => function () { return new Helper\Form\Select; },
            'textarea'   => function () { return new Helper\Form\Textarea; },
        ]);
        
        $inputObj = function() use ($input) { return new Helper\Form\Input($input); };
        $fieldObj = function () use ($fields) { return new Helper\Form\Field($fields); };

        $template = new Template(new EscaperFactory, new TemplateFinder, new HelperLocator([
            'anchor'        => function () { return new Helper\Anchor; },
            'attribs'       => function () { return new Helper\Attribs; },
            'base'          => function () { return new Helper\Base; },
            'datetime'      => function () { return new Helper\Datetime; },
            'escape'        => function () { return new Helper\Escape(new EscaperFactory); },
            'field'         => $fieldObj,
            'image'         => function () { return new Helper\Image; },
            'input'         => $inputObj,
            'links'         => function () { return new Helper\Links; },
            'metas'         => function () { return new Helper\Metas; },
            'ol'            => function () { return new Helper\Ol; },
            'radios'        => function () { return new Helper\Form\Radios(new Helper\Form\Input\Checked); },
            'scripts'       => function () { return new Helper\Scripts; },
            'scriptsFoot'   => function () { return new Helper\Scripts; },
            'select'        => function () { return new Helper\Form\Select; },
            'styles'        => function () { return new Helper\Styles; },
            'tag'           => function () { return new Helper\Tag; },
            'title'         => function () { return new Helper\Title; },
            'textarea'      => function () { return new Helper\Form\Textarea; },
            'ul'            => function () { return new Helper\Ul; },
            'redirect'      => function () { return new \Modus\Template\Helper\Redirect(); },
            'paginator'     => function () { return new \Modus\Template\Helper\Paginator(new \Pagerfanta\View\DefaultView()); },
        ]));
        
        $twostep = new TwoStep($template, new FormatTypes());
        $twostep->setInnerPaths($viewPaths);
        $twostep->setOuterView($layout);
        $this->template = $twostep;
        
    }
    
    public function getTemplate() {
        return $this->template;
    }
    
    public function generateNewTemplate() {
        $this->init($this->layout, $this->viewPaths);
        return $this->template;
    }
}