<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Dto\Filter;

use Illuminate\Contracts\Support\Arrayable;
use Patrikjak\Utils\Common\Enums\Filter\FilterType;

abstract class BaseFilterCriteria implements Arrayable
{
    abstract public function getType(): FilterType;

    /**
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;

    public function __construct(public string $column)
    {
    }
}