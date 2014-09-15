<?php

namespace Rmasters\ReflectionCast;

use ReflectionClass;

class CastWrapper
{
    protected $instance;
    protected $reflected;

    public function __construct($instance)
    {
        if (!is_object($instance)) {
            throw new \InvalidArgumentException('CastWrapper is intended for proxying access to objects');
        }

        $this->instance = $instance;
        $this->reflected = new ReflectionClass($this->instance);
    }

    public function __get($name)
    {
        return $this->instance->$name;
    }

    public function __set($name, $value)
    {
        return $this->proxySetter($name, $value);
    }

    public function __call($method, array $args)
    {
        return $this->proxyMethod($method, $args);
    }

    protected function proxyMethod($methodName, array $args)
    {
        if ($this->reflected->hasMethod($methodName)) {
            $method = $this->reflected->getMethod($methodName);
            if (!$method->isPublic()) {
                throw new \Exception('Cannot call non-public method, '.$methodName);
            }

            $methodParams = $this->parseMethod($method->getDocComment());

            // List of types (mapped through getType()) for method signature in exception
            $types = array_map([$this, 'getType'], $methodParams->getParams());

            foreach ($args as $index => $value) {
                $expectedType = $this->getType($methodParams->getParamByIndex($index));
                if (!$this->checkType($expectedType, $value)) {
                    throw new \InvalidArgumentException(sprintf(
                        'Expected %s %s for %s::%s, got %s',
                        preg_match('/^(a|e|i|o|u)/', $expectedType) ? 'an' : 'a',
                        $expectedType,
                        $this->reflected->getName(),
                        sprintf('%s(%s)', $methodName, implode(', ', $types)),
                        gettype($value)
                    ));
                }
            }

            return $method->invokeArgs($this->instance, $args);
        }

        return call_user_func_array([$this->instance, $methodName], $args);
    }

    protected function proxySetter($propertyName, $value)
    {
        if ($this->reflected->hasProperty($propertyName)) {
            $property = $this->reflected->getProperty($propertyName);
            if (!$property->isPublic()) {
                throw new \Exception('Cannot set non-public property, '.$propertyName);
            }

            $doc = $this->parseProperty($property->getDocComment());

            $expectedType = $this->getType($doc->getType());
            if (!$this->checkType($expectedType, $value)) {
                throw new \InvalidArgumentException(sprintf(
                    'Expected %s %s for %s::%s, got %s',
                    preg_match('/^(a|e|i|o|u)/', $expectedType) ? 'an' : 'a',
                    $expectedType,
                    $this->reflected->getName(),
                    sprintf('$%s', $propertyName),
                    gettype($value)
                ));
            }

            $property->setValue($this->instance, $value);
        }

        $this->instance->$propertyName = $value;
    }

    protected function checkType($expectedType, $value)
    {
        return gettype($value) == $this->getType($expectedType);
    }

    protected function getType($type)
    {
        // Map of phpdoc types to PHP type
        static $typeAliases = [
            'int' => 'integer',
            'bool' => 'boolean',
        ];

        if (array_key_exists($type, $typeAliases)) {
            return $typeAliases[$type];
        }
        return $type;
    }

    protected function parseMethod($doc)
    {
        static $parser;
        if (!isset($parser)) {
            $parser = new MethodParser;
        }

        return $parser->parse($doc);
    }

    protected function parseProperty($doc)
    {
        static $parser;
        if (!isset($parser)) {
            $parser = new PropertyParser;
        }

        return $parser->parse($doc);
    }
}
