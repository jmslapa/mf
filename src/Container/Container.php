<?php

namespace Mf\Container;

use Mf\Exceptions\Container\ContextualBindingException;
use ReflectionClass;
use TypeError;

final class Container
{

    private static ?Container $instance = null;

    private array $bindings;

    private array $contextualBindings;

    private function __construct()
    {
        $this->bindigs = [];
        $this->contextualBindings = [];
    }

    public static function getInstance(): Container
    {
        if (is_null(self::$instance)) {
            self::$instance = new Container;
        }
        return self::$instance;
    }

    public function resolve(string $bindingName, $args = [])
    {
        if (isset($this->bindings[$bindingName])) {
            $value = $this->bindings[$bindingName];
            if (is_callable($value)) {
                return $value();
            } elseif (class_exists($value)) {
                return $this->resolve($value);
            } else {
                return $value;
            }
        } elseif (class_exists($bindingName) || interface_exists($bindingName) or trait_exists($bindingName)) {
            return $this->make($bindingName, $args);
        } else {
            return null;
        }
    }

    public function make(string $bindingName, $args)
    {
        $metaClass = new ReflectionClass($bindingName);
        $constructor = $metaClass->getConstructor();
        if (!$constructor) {
            return $metaClass->newInstance();
        }
        $parameters = array_map(function ($param) {
            return (object) [
                'type' => $type = ($param->getType() ? $param->getType()->getName() : null),
                'name' => $param->getName(),
                'isClass' => class_exists($type),
                'isInterface' => interface_exists($type)
            ];
        }, $constructor->getParameters());
        if (count($parameters)) {
            foreach ($parameters as $index => $param) {
                if (isset($args[$param->name])) {
                    $parameters[$index] = $args[$param->name];
                } elseif ($param->isClass || $param->isInterface) {
                    if (isset($this->contextualBindings[$bindingName])) {
                        $cb = $this->contextualBindings[$bindingName];
                        if ($cb['abstract'] === $param->type) {
                            $parameters[$index] = $this->resolve($cb['concrete']);
                        } else {
                            throw new ContextualBindingException(
                                "The linked abstract is different from the one defined in the $bindingName constructor."
                            );
                        }
                    } else {
                        $parameters[$index] = $this->resolve($param->type);
                    }
                } elseif ($param->type) {
                    if (in_array($param->type, [
                        'string',
                        'integer',
                        'float',
                        'boolean',
                        'array',
                        'object',
                        'resource',
                    ])) {
                        unset($parameters[$index]);
                    } else {
                        throw new TypeError(
                            "$param->type is not a valid class on {$metaClass->getName()}::__construct()."
                        );
                    }
                } else {
                    unset($parameters[$index]);
                }
            }
        }
        return $metaClass->newInstanceArgs($parameters);
    }

    public function bind(string $bindingName, $value)
    {
        $this->bindings[$bindingName] = $value;
    }

    public function contextualBinding(string $subject, string $abstract, string $concrete)
    {
        $args = get_defined_vars();
        foreach ($args as $arg => $value) {
            if (!class_exists($value) && $arg !== 'abstract') {
                throw new ContextualBindingException("The $value($arg) argument is a non-existant or invalid class.");
            }
            if ((!interface_exists($value) && !class_exists($value)) && $arg === 'abstract') {
                throw new ContextualBindingException(
                    "The $value($arg) argument is a non-existant or invalid interface|class."
                );
            }
        }
        $this->contextualBindings[$subject]['abstract'] = $abstract;
        $this->contextualBindings[$subject]['concrete'] = $concrete;
    }
}
