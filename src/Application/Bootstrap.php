<?php

namespace Modus\Application;

use Aura\Di;
use Aura\Web;

use Modus\Router;
use Modus\ErrorLogging as Log;
use Modus\Common\Route\Exception\NotFoundException;
use Modus\Auth;
use Modus\Config\Config;
use Modus\Responder\Exception;

class Bootstrap
{

    protected $config;
    protected $router;
    protected $responseMgr;
    protected $errorLog;
    protected $eventLog;
    protected $authService;
    protected $depInj;

    public function __construct(
        Config $config,
        Router\Standard $router,
        Auth\Service $authService,
        Log\Manager $handler
    ) {
        $this->config = $config;
        $this->depInj = $config->getContainer();
        $this->router = $router;
        $this->authService = $authService;
        $this->errorLog = $handler->getLogger('error');
        $this->eventLog = $handler->getLogger('event');

        $this->authService->resume();
    }

    public function execute()
    {
        $config = $this->config->getConfig();


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
            if (isset($config['error_page']['404'])) {
                $lastRoute = $this->router->getLastRoute();
                $this->eventLog->info(sprintf("No route was found that matches '%s'", $lastRoute));

                $responder = $this->depInj->newInstance($config['error_page']['404']);
                $responder->process([]);
                $responder->sendResponse();
                return;
            }

            // No 404 page was set, so let's throw the exception.
            throw $e;
        }

        try {
            // We put the responder first, so that if the content type is unavailable, we don't execute the
            // request
            $responder = $this->depInj->newInstance($responder);
        } catch (Exception\ContentTypeNotValidException $e) {
            if (isset($config['error_page']['406'])) {
                $responder = $this->depInj->newInstance($config['error_page']['406']);
                $responder->process([]);
                $responder->sendResponse();
                return;
            }

            throw $e;
        }

        $object = $this->depInj->newInstance($action);
        $result = call_user_func_array([$object, $method], $params);

        if (!$result) {
            $result = [];
        }
        $responder->process($result);
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
