<?php

namespace Modus\Application;

use Aura\Di;
use Aura\Web;

use Modus\Router;
use Modus\ErrorLogging as Log;
use Modus\Common\Route\Exception\NotFoundException;
use Modus\Auth;
use Modus\Config\Config;

class Bootstrap
{

    protected $config;
    protected $router;
    protected $responseMgr;
    protected $errorLog;
    protected $eventLog;
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
        $this->errorLog = $handler->getLogger('error');
        $this->eventLog = $handler->getLogger('event');

        $this->authService->resume();
    }

    public function execute()
    {

        try {
            $routepath = $this->evaluateRoute();
            $route = $routepath->values;

            $action = $route['action'];
            $responder = $route['responder'];
            $method = $route['method'];

            $params = $route;
            unset($params['action']);
            unset($params['responder']);
            unset($params['method']);
        } catch (NotFoundException $e) {
            $config = $this->config->getConfig();
            if (isset($config['error_page']['404'])) {
                $lastRoute = $this->router->getLastRoute();
                $this->eventLog->info(sprintf("No route was found that matches '%s'", $lastRoute));

                $responder = $this->depInj->newInstance($config['error_page']['404']);
                $responder->processResponse([]);
                $responder->sendResponse();
                return;
            }

            // No 404 page was set, so let's throw the exception.
            throw $e;
        }

        $object = $this->depInj->newInstance($action);
        $result = call_user_func_array([$object, $method], $params);

        $responder = $this->depInj->newInstance($responder);
        $responder->processResponse($result);
        $responder->sendResponse();
    }

    public function evaluateRoute()
    {
        $router = $this->router;
        $routepath = $router->determineRouting();
        if (!$routepath) {
            throw new NotFoundException('The route "' . $router->getLastRoute() . '" was not found');
        }
        return $routepath;
    }
}
