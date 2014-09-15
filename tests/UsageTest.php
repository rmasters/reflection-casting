<?php

namespace Rmasters\ReflectionCast\Tests;

use Rmasters\ReflectionCast\CastWrapper;
use Rmasters\ReflectionCast\Tests\Fixtures\User;

class UsageTest extends \PHPUnit_Framework_TestCase
{
    public function testSetterMethod()
    {
        $instance = new CastWrapper(new User);
        $instance->setId(23);
        $this->assertEquals(23, $instance->id);

        $this->setExpectedException('InvalidArgumentException', 'Expected an integer for Rmasters\ReflectionCast\Tests\Fixtures\User::setId(integer), got string');
        $instance->setId("42");
    }

    public function testPropertySet()
    {
        $instance = new CastWrapper(new User);
        $instance->enabled = true;
        $this->assertTrue($instance->enabled);

        $this->setExpectedException('InvalidArgumentException', 'Expected a boolean for Rmasters\ReflectionCast\Tests\Fixtures\User::$enabled, got string');
        $instance->enabled = "yes";
    }

    public function testSettingNonPublicProperty()
    {
        $instance = new CastWrapper(new User);

        $this->setExpectedException('Exception', 'Cannot set non-public property, favouriteColour');
        $instance->favouriteColour = "magic";
    }

    public function testCallingNonPublicMethod()
    {
        $instance = new CastWrapper(new User);

        $this->setExpectedException('Exception', 'Cannot call non-public method, calculateAirspeedVelocity');
        $instance->calculateAirspeedVelocity('european');
    }
}
