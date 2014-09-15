<?php

namespace Rmasters\ReflectionCast;

class Method
{
    protected $params;
    protected $paramsByIndex;
    protected $paramsByName;

    public function __construct()
    {
        $this->params = [];
        $this->paramsByIndex = [];
        $this->paramsByName = [];
    }

    public function addParam($index, $type, $name = null)
    {
        $this->params[] = $type;
        $param =& $this->params[count($this->params)-1];

        $this->paramsByIndex[$index] = $param;
        if ($name) {
            $this->paramsByName[$name] = $param;
        }
    }

    public function getParamByIndex($index)
    {
        return count($this->paramsByIndex) - 1 >= $index ? $this->paramsByIndex[$index] : false;
    }

    public function getParamByName($name)
    {
        return array_key_exists($name, $this->paramsByName) ? $this->paramsByName[$name] : false;
    }

    public function getParams()
    {
        return $this->params;
    }
}
