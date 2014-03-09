<?php

namespace Modus\Template\Helper;

use Aura\View\Helper\AbstractHelper;
use Pagerfanta\View;
use Pagerfanta;

class Paginator extends AbstractHelper {

    protected $defaultView;

    public function __construct(View\ViewInterface $defaultView) {
        $this->defaultView = $defaultView;
    }

    public function __invoke(Pagerfanta\PagerfantaInterface $pagerfanta, $route, array $options = array()) {
        $routeGenerator = function($page) use ($route) { return $route . '/' . $page; };
        return $this->defaultView->render($pagerfanta, $routeGenerator, $options);
    }
}