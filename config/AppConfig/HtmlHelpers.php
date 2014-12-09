<?php

namespace AppConfig;

use Aura\Di;

/**
 * Set up the setters and other parameters passed into actions.
 *
 * Class Action
 * @package AppConfig\ADR
 */
class HtmlHelpers extends Di\Config
{
    public function define(Di\Container $di)
    {
        $di->params['Aura\Html\HelperLocator']['map'] = [
            'messages' => $di->lazyNew('Modus\Template\Helper\Messages'),
            'paginator' => $di->lazyNew('Modus\Template\Helper\Paginator'),
            'linkgenerator' => $di->lazyNew('Modus\Template\Helper\LinkGenerator'),
            'a' => $di->lazyNew('Aura\Html\Helper\Anchor'),
            'anchor' => $di->lazyNew('Aura\Html\Helper\Anchor'),
            'base' => $di->lazyNew('Aura\Html\Helper\Base'),
            'escape' => $di->LazyNew('Aura\Html\Escaper'),
            'form' => $di->lazyNew('Aura\Html\Helper\Form'),
            'img' => $di->lazyNew('Aura\Html\Helper\Img'),
            'image' => $di->lazyNew('Aura\Html\Helper\Img'),
            'input' => $di->lazyNew('Aura\Html\Helper\Input'),
            'label' => $di->lazyNew('Aura\Html\Helper\Label'),
            'links' => $di->lazyNew('Aura\Html\Helper\Links'),
            'metas' => $di->lazyNew('Aura\Html\Helper\Metas'),
            'ol' => $di->lazyNew('Aura\Html\Helper\Ol'),
            'scripts' => $di->lazyNew('Aura\Html\Helper\Scripts'),
            'scriptsFoot' => $di->lazyNew('Aura\Html\Helper\Scripts'),
            'styles' => $di->lazyNew('Aura\Html\Helper\Styles'),
            'tag' => $di->lazyNew('Aura\Html\Helper\Tag'),
            'title' => $di->lazyNew('Aura\Html\Helper\Title'),
            'ul' => $di->lazyNew('Aura\Html\Helper\Ul'),
        ];

        /**
         * Session Flash Messages
         */
        $di->params['Modus\Template\Helper\Messages'] = [
            'session' => $di->lazyNew('Modus\Session\Aura'),
        ];

        /**
         * Paginator
         */
        $di->params['Modus\Template\Helper\Paginator'] = [
            'defaultView' => $di->lazyNew('Pagerfanta\View\DefaultView'),
        ];

        /**
         * Link Generator
         */
        $di->params['Modus\Template\Helper\LinkGenerator'] = [
            'standardRouter' => $di->lazyNew('Modus\Router\Standard'),
        ];

        /**
         * Aura\Html\Helper\Input
         */
        $di->params['Aura\Html\Helper\Input']['map'] = array(
            'button' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'checkbox' => $di->lazyNew('Aura\Html\Helper\Input\Checkbox'),
            'color' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'date' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'datetime' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'datetime-local' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'email' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'file' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'hidden' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'image' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'month' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'number' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'password' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'radio' => $di->lazyNew('Aura\Html\Helper\Input\Radio'),
            'range' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'reset' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'search' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'select' => $di->lazyNew('Aura\Html\Helper\Input\Select'),
            'submit' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'tel' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'text' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'textarea' => $di->lazyNew('Aura\Html\Helper\Input\Textarea'),
            'time' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'url' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
            'week' => $di->lazyNew('Aura\Html\Helper\Input\Generic'),
        );

        /**
         * Aura\Html\Escaper
         */
        $di->params['Aura\Html\Escaper'] = array(
            'html' => $di->lazyNew('Aura\Html\Escaper\HtmlEscaper'),
            'attr' => $di->lazyNew('Aura\Html\Escaper\AttrEscaper'),
            'css' => $di->lazyNew('Aura\Html\Escaper\CssEscaper'),
            'js' => $di->lazyNew('Aura\Html\Escaper\JsEscaper'),
        );

        /**
         * Aura\Html\Escaper\AttrEscaper
         */
        $di->params['Aura\Html\Escaper\AttrEscaper'] = array(
            'html' => $di->lazyNew('Aura\Html\Escaper\HtmlEscaper'),
        );

        /**
         * Aura\Html\Helper\AbstractHelper
         */
        $di->params['Aura\Html\Helper\AbstractHelper']['escaper'] = $di->lazyNew('Aura\Html\Escaper');
    }
}
