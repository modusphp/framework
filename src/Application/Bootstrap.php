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

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Router\Standard
     */
    protected $router;

    /**
     * @var \Monolog\Logger
     */
    protected $errorLog;

    /**
     * @var \Monolog\Logger
     */
    protected $eventLog;

    /**
     * @var Auth\Service
     */
    protected $authService;

    /**
     * @var Di\Container
     */
    protected $serviceLocator;

    /**
     * @param Config $config
     * @param Router\Standard $router
     * @param Auth\Service $authService
     * @param Log\Manager $handler
     * @throws Log\Exception\LoggerNotRegistered
     */
    public function __construct(
        Config $config,
        Router\Standard $router,
        Auth\Service $authService,
        Log\Manager $handler
    ) {
        $this->config = $config;
        $this->serviceLocator = $config->getContainer();
        $this->router = $router;
        $this->authService = $authService;
        $this->errorLog = $handler->getLogger('error');
        $this->eventLog = $handler->getLogger('event');

        $this->authService->resume();
    }

    /**
     * @throws Exception\ContentTypeNotValidException
     * @throws NotFoundException
     * @throws \Exception
     */
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

                $responder = $this->serviceLocator->newInstance($config['error_page']['404']);
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
            $responder = $this->serviceLocator->newInstance($responder);
        } catch (Exception\ContentTypeNotValidException $e) {
            if (isset($config['error_page']['406'])) {
                $responder = $this->serviceLocator->newInstance($config['error_page']['406']);
                $responder->process([]);
                $responder->sendResponse();
                return;
            }

            throw $e;
        }

        $object = $this->serviceLocator->newInstance($action);
        $result = call_user_func_array([$object, $method], $params);

        if (!$result) {
            $result = [];
        }
        $responder->process($result);
        $responder->sendResponse();
    }

    /**
     * @return \Aura\Router\Route
     * @throws NotFoundException
     */
    protected function evaluateRoute()
    {
        $router = $this->router;
        $routepath = $router->determineRouting();
        if (!$routepath) {
            throw new NotFoundException('The route "' . $router->getLastRoute() . '" was not found');
        }
        return $routepath;
    }
}
