<?php

namespace Mf\Contracts\Routes;

interface RouteGroupContract
{
    public function isRequested(string $method, string $path): bool;

    public function invokeAction(): void;

    public function namespace(string $namespace): self;
}
