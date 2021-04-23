<?php

namespace Mf\Contracts\Routes;

interface RouterContract
{
    public function getUri(): string;

    public function getMethod(): string;

    public function run(string $method, string $uri): void;
}
