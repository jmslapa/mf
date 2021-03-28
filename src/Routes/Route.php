<?php

namespace Mf\Routes;

use Mf\Contracts\Routes\RouteContract;
use Mf\Contracts\Http\ControllerContract;
use Mf\Contracts\Routes\RouteGroupContract;

class Route implements RouteContract
{
    private string $method;
    private string $path;
    private string $controller;
    private string $action;
    private string $namespace;

    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const PATCH = 'PATCH';
    const DELETE = 'DELETE';

    private function __construct(string $method, string $path, string $handler)
    {
        [$controller, $action] = explode('@', $handler);

        $this->method = $method;
        $this->path = $path;
        $this->namespace = '';
        $this->controller = $controller;
        $this->action = $action;
    }

    public static function get(string $path, string $handler): RouteContract
    {
        return new self(self::GET, $path, $handler);
    }

    public static function post(string $path, string $handler): RouteContract
    {
        return new self(self::POST, $path, $handler);
        
    }

    public static function put(string $path, string $handler): RouteContract
    {
        return new self(self::PUT, $path, $handler);        
    }

    public static function patch(string $path, string $handler): RouteContract
    {
        return new self(self::PATCH, $path, $handler);
    }

    public static function delete(string $path, string $handler): RouteContract
    {
        return new self(self::DELETE, $path, $handler);
    }

    public function isRequested(string $method, string $path): bool
    {
        return $this->method === $method && $this->path === $path;
    }

    public function invokeAction(): void
    {
        $class = "$this->namespace\\$this->controller";
        $action = $this->action;
        (new $class)->$action();   
    }

    public function namespace(string $namespace) : self
    {
        $this->namespace = $namespace;
        return $this;
    }

    public static function group(array $routes) : RouteGroupContract
    {
        return new RouteGroup($routes);
    }
}