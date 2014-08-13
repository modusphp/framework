<?php

namespace Modus\Application;

use Aura\Di;
use Aura\Web;

use Modus\Router;
use Modus\ErrorLogging as Log;
use Modus\Common\Controller\Exception;

class Bootstrap {
    
    protected $config;
    protected $di;
    protected $router;
    protected $responseMgr;
    protected $errorHandler;
    protected $context;

    public function __construct(
        $config,
        Di\Container $di,
        Web\WebFactory $context,
        Router\Standard $router,
        Log\Manager $handler
    ) {
        $this->di = $di;
        $this->context = $context->newRequest();
        $this->router = $router;
        $this->errorHandler = $handler;

        $this->config = $config;
    }
    
    public function execute() {
        $router = $this->router;
        $routepath = $router->determineRouting($this->context->server->get());
        if(!$routepath) {
            throw new Exception\NotFound('The route "' . $router->getLastRoute() . '" was not found');
        }

        $route = $routepath->values;

        $action = $route['action'];
        $responder = $route['responder'];
        $method = $route['method'];

        $params = $route;
        unset($params['action']);
        unset($params['responder']);
        unset($params['method']);

        $object = $this->di->newInstance($action);
        $result = call_user_func_array([$object, $method], $params);

        $responder = $this->di->newInstance($responder);
        $responder->processResponse($result);
        $responder->sendResponse();
    }
}