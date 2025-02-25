<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Unit\Common\Traits;

use Orchestra\Testbench\TestCase;
use Patrikjak\Utils\Tests\Unit\Common\Traits\Mocks\TestEnum;

class EnumValuesTest extends TestCase
{
    public function testCanGetAll(): void
    {
        $all = TestEnum::getAll();

        $this->assertEquals([
            'FIRST' => TestEnum::FIRST,
            'SECOND' => TestEnum::SECOND,
            'THIRD' => TestEnum::THIRD,
        ], $all);
    }

    public function testCanGetKeys(): void
    {
        $keys = TestEnum::getKeys();

        $this->assertEquals(['FIRST', 'SECOND', 'THIRD'], $keys);
    }

    public function testCanGetValues(): void
    {
        $values = TestEnum::getValues();

        $this->assertEquals(['first', 'second', 'third'], $values);
    }
}
