<?php

namespace Mf\Contracts\Routes;

use Mf\Contracts\Http\ControllerContract;

interface RouteContract
{
    public function isRequested(string $method, string $path): bool;

    public function invokeAction(): void;

    public function namespace(string $namespace): self;

    /**
     * @param string $path ex: "/home"
     * @param string $handler ex: "Controller@action"
     * @return self
     */
    public static function get(string $path, string $handler): self;

    /**
     * @param string $path ex: "/home"
     * @param string $handler ex: "Controller@action"
     * @return self
     */
    public static function post(string $path, string $handler): self;

    /**
     * @param string $path ex: "/home"
     * @param string $handler ex: "Controller@action"
     * @return self
     */
    public static function put(string $path, string $handler): self;

    /**
     * @param string $path ex: "/home"
     * @param string $handler ex: "Controller@action"
     * @return self
     */
    public static function patch(string $path, string $handler): self;

    /**
     * @param string $path ex: "/home"
     * @param string $handler ex: "Controller@action"
     * @return self
     */
    public static function delete(string $path, string $handler): self;


    public static function group(array $routes): RouteGroupContract;
}
