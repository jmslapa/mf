<?php

namespace Mf\Routes;

use Mf\Contracts\Routes\RouteContract;
use Mf\Contracts\Routes\RouteGroupContract;

class RouteGroup implements RouteGroupContract
{
    private array $routes;
    private string $namespace;
    private Route $requested;

    public function __construct(array $routes)
    {
        $this->setRoutes($routes);
    }

    protected function setRoutes(array $routes) 
    {
        $invalidRoutes = array_filter($routes, fn($route) => !concrete_of($route, RouteContract::class));

        if(!count($routes) || count($invalidRoutes)) {
            throw new \InvalidArgumentException('The first argument must be a not empty array of '.RouteContract::class.' objects');
        }

        $this->routes = $routes;
    }

    public function isRequested(string $method, string $path): bool
    {
        $requested = array_shift(
            array_filter($this->routes, fn($route) => $route->isRequested($method, $path))
        );

        if($requested) {
            $this->requested = $requested;
            return true;
        }

        return false;
    }

    public function invokeAction() : void
    {
        $this->requested->invokeAction();
    }

    public function namespace(string $namespace) : RouteGroupContract
    {
        $this->namespace = $namespace;
        foreach ($this->routes as $route) {
            $route->namespace($this->namespace);
        }
        return $this;
    }
}