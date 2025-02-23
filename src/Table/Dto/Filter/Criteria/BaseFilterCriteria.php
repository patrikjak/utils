<?php

namespace Patrikjak\Utils\Table\Dto\Filter\Criteria;

use Illuminate\Contracts\Support\Arrayable;
use Patrikjak\Utils\Table\Dto\Filter\FilterType;

abstract class BaseFilterCriteria implements Arrayable
{
    public function __construct(public string $column)
    {
    }

    abstract public function getType(): FilterType;

    /**
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;
}