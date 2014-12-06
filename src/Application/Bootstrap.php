<?php

namespace Modus\Application;

use Aura\Di;
use Aura\Web;

use Modus\Router;
use Modus\ErrorLogging as Log;
use Modus\Common\Route\NotFoundException;
use Modus\Auth;
use Modus\Config\Config;

class Bootstrap {
    
    protected $config;
    protected $router;
    protected $responseMgr;
    protected $errorHandler;
    protected $request;
    protected $authService;
    protected $depInj;

    public function __construct(
        Config $config,
        Web\Request $request,
        Router\Standard $router,
        Auth\Service $authService,
        Log\Manager $handler
    ) {
        $this->config = $config;
        $this->depInj = $config->getDI();
        $this->request = $request;
        $this->router = $router;
        $this->authService = $authService;
        $this->errorHandler = $handler;
    }
    
    public function execute() {
        $this->authService->resume();

        $router = $this->router;
        $routepath = $router->determineRouting($this->request->server->get());
        if(!$routepath) {
            throw new NotFoundException('The route "' . $router->getLastRoute() . '" was not found');
        }

        $route = $routepath->values;

        $action = $route['action'];
        $responder = $route['responder'];
        $method = $route['method'];

        $params = $route;
        unset($params['action']);
        unset($params['responder']);
        unset($params['method']);

        $object = $this->depInj->newInstance($action);
        $result = call_user_func_array([$object, $method], $params);

        $responder = $this->depInj->newInstance($responder);
        $responder->processResponse($result);
        $responder->sendResponse();
    }
}