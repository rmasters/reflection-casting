<?php

namespace Rmasters\ReflectionCast\Tests\Fixtures;

class User
{
    /** @var int */
    public $id;

    /** @var boolean */
    public $enabled;

    /** @var string */
    private $favouriteColour;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    private function calculateAirspeedVelocity($swallowBreed)
    {
    }
}
