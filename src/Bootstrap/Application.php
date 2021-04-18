<?php

namespace Mf\Bootstrap;

use Mf\Exceptions\Handler;
use Mf\Routes\Router;

abstract class Application
{
    private $errorHandler;

    public function __construct()
    {
        $this->errorHandler = new Handler();
        $this->boot();
        $this->run();
    }

    protected abstract function loadRoutes() : array;

    protected function boot()
    {
    }

    final protected function run()
    {
        try {
            $routes = $this->loadRoutes();
            new Router($routes);

        } catch (\Throwable $th) {
            $this->errorHandler->render($th);
        }
    }
}