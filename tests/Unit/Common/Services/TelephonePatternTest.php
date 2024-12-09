<?php

namespace Patrikjak\Utils\Tests\Unit\Common\Services;

use Orchestra\Testbench\TestCase;
use Patrikjak\Utils\Common\Services\TelephonePattern;

class TelephonePatternTest extends TestCase
{
    public function testGetExample(): void
    {
        $this->assertSame('+421123456789', TelephonePattern::SK->getExample());
        $this->assertSame('+420123456789', TelephonePattern::CZ->getExample());
    }
}