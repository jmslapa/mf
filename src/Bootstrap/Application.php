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
        $this->init();
    }

    abstract protected function loadRoutes(): array;

    protected function boot()
    {
    }

    final protected function run()
    {
        new Router($this->loadRoutes());
    }

    final protected function init()
    {
        try {
            $this->boot();
            $this->run();
        } catch (\Throwable $th) {
            $this->errorHandler->render($th);
        }
    }
}
