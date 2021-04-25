<?php

namespace Mf\Exceptions\Container;

use Exception;

class ContextualBindingException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
