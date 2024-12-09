<?php

namespace Patrikjak\Utils\Tests\Unit\Common\Helpers;

use Orchestra\Testbench\TestCase;
use Patrikjak\Utils\Common\Helpers\GrammaticalGender;

class GrammaticalGenderTest extends TestCase
{
    public function testGender(): void
    {
        $this->assertEquals(1, GrammaticalGender::MASCULINE);
        $this->assertEquals(2, GrammaticalGender::FEMININE);
        $this->assertEquals(3, GrammaticalGender::NEUTER);
    }
}