<?php

namespace Modus\Application;

use Aura\Di;
use Aura\Payload\Payload;
use Modus\Response\ResponseManager;
use Modus\Route;
use Modus\ErrorLogging as Log;
use Modus\Common\Route\Exception\NotFoundException;
use Modus\Auth;
use Modus\Config\Config;
use Modus\Response\Exception;
use Psr\Http\Message\ServerRequestInterface;

class Bootstrap
{

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Route\Manager
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
     * @var ResponseManager
     */
    protected $responseManager;

    /**
     * @var ServerRequestInterface
     */
    protected $serverRequest;

    /**
     * @param  Config              $config
     * @param  Route\Manager       $router
     * @param  Auth\Service        $authService
     * @param  Log\Manager         $handler
     * @param  ResponseManager     $responseManager
     * @throws Log\Exception\LoggerNotRegistered
     */
    public function __construct(
        Config $config,
        Di\Container $di,
        Route\Manager $router,
        ServerRequestInterface $serverRequest,
        Auth\Service $authService,
        Log\Manager $handler,
        ResponseManager $responseManager
    ) {
        $this->config = $config;
        $this->serviceLocator = $di;
        $this->router = $router;
        $this->authService = $authService;
        $this->errorLog = $handler->getLogger('error');
        $this->eventLog = $handler->getLogger('event');
        $this->responseManager = $responseManager;
        $this->serverRequest = $serverRequest;

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

        // Figure out the route information.
        try {
            $routepath = $this->evaluateRoute();
            $route = $routepath->extras;
            $components = $this->determineRouteComponents($route);
            $params = $route;
            unset($params['action']);
            unset($params['responder']);
        } catch (NotFoundException $e) {
            if (isset($config['error_page']['404'])) {
                $lastRoute = $this->serverRequest->getUri()->getPath();
                $this->eventLog->info(sprintf("No route was found that matches '%s'", $lastRoute));

                $responder = $this->serviceLocator->newInstance($config['error_page']['404']);
                $this->responseManager->process(new Payload(), $responder);
                return;
            }

            // No 404 page was set, so let's throw the exception.
            throw $e;
        }

        // Load the responder that we identified from routes.
        $responder = $this->serviceLocator->newInstance($components['responderClass']);

        // Load the action we identified from routes, if one exists.
        if (!is_null($components['actionClass'])) {
            $action = $this->serviceLocator->newInstance($components['actionClass']);

            // Call the action.
            $result = call_user_func_array([$action, '__invoke'], $params);
        }

        // Let's not leave the response hanging...
        if (!isset($result) || !$result) {
            $result = new Payload();
        }

        // Call and send the response, if possible.
        $this->responseManager->process($result, $responder);
    }

    /**
     * @return \Aura\Router\Route
     * @throws NotFoundException
     */
    protected function evaluateRoute() : \Aura\Router\Route
    {
        $router = $this->router;
        $routepath = $router->matchRoute($this->serverRequest);
        if (!$routepath) {
            throw new NotFoundException('The route "' . $this->serverRequest->getUri()->getPath() . '" was not found');
        }
        return $routepath;
    }

    /**
     * @param array $components
     * @return array
     */
    protected function determineRouteComponents(array $components = []) : array
    {
        if (!isset($components['responder'])) {
            $responder = 'Modus\Responder\NoContent204Response';
        } else {
            $responder = $components['responder'];
        }

        if (isset($components['action'])) {
            $action = $components['action'];
        } else {
            $action = null;
        }

        return [
            'responderClass' => $responder,
            'actionClass' => $action,
        ];
    }
}
