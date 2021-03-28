<?php

namespace Mf\Bootstrap;

use Mf\Exceptions\Handler;
use Mf\Routes\Router;

abstract class Application
{
    private $errorHandler;

    public function __construct()
    {
        $this->boot();
    }

    protected abstract function loadRoutes() : array;

    protected function boot()
    {
        $this->errorHandler = new Handler();

        try {
            $routes = $this->loadRoutes();
            new Router($routes);

        } catch (\Throwable $th) {
            $this->errorHandler->render($th);
        }
    }
}