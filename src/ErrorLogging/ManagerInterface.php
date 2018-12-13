<?php

namespace Modus\ErrorLogging;

interface ManagerInterface
{
    /**
     * Turn the error logger on or off. E.g. Whoops and BooBoo support
     * registering and deregistering their error handling capabilities.
     *
     * @param $bool
     */
    public function registerErrorHandler($bool);

    /**
     * Get the current error handler object.
     *
     * @return object
     */
    public function getErrorHandler();

    /**
     * Return all the error handlers (if one is not specified) or a single
     * error handler (if one is specified).
     *
     * @param  $loggerName
     * @return object|array
     * @throws Exception\LoggerNotRegistered
     */
    public function getLogger($loggerName);
}
