<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Services\QueryBuilder\Filters;

use Patrikjak\Utils\Common\Services\QueryBuilder\ResolvesColumnMask;

abstract class AbstractFilter
{
    use ResolvesColumnMask;
}
