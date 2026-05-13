<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Contracts\Filter;

interface FilterDefinition
{
    public function getType(): string;

    /**
     * Key-value pairs rendered as data-* attributes on the filter option element.
     * These are forwarded as query params to the filter form modal endpoint.
     *
     * @return array<string, string|int|float|null>
     */
    public function getFilterData(): array;
}
