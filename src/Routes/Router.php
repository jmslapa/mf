<?php

namespace Mf\Routes;

use Mf\Contracts\Routes\RouteContract;
use Mf\Contracts\Routes\RouteGroupContract;
use Mf\Contracts\Routes\RouterContract;

class Router implements RouterContract
{
    private array $routes;

    public function __construct(array $routes)
    {
        $this->setRoutes($routes);
        $this->run($this->getMethod(), $this->getUri());
    }

    /**
     * @throws \InvalidArgumentException
     * @param array $routes
     * @return void
     */
    protected function setRoutes(array $routes) : void
    {
        $invalidRoutes = array_filter($routes, fn($route) => !concrete_of($route, RouteContract::class) && !concrete_of($route, RouteGroupContract::class));
        
        if(!count($routes) || count($invalidRoutes)) {
            throw new \InvalidArgumentException('The first argument must be a not empty array of '.RouteContract::class.' objects');
        }

        $this->routes = $routes;
    }

    public function getUri() : string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function run(string $method, string $uri) : void
    {
        foreach ($this->routes as $route) {
            if($route->isRequested($method, $uri)) {
                $route->invokeAction();
                return;
            }
        }
        throw new \Exception("Página [$method]$uri não encontrada");
    }
}