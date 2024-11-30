<?php

namespace Patrikjak\Utils\Tests\Unit\Common\Traits\Mocks;

use Patrikjak\Utils\Common\Traits\EnumValues;

enum TestEnum: string
{
    use EnumValues;

    case FIRST = 'first';
    case SECOND = 'second';
    case THIRD = 'third';
}
