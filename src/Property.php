<?php

namespace Rmasters\ReflectionCast;

class Property
{
    protected $type;

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}
