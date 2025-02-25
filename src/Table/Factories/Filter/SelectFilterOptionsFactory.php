<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Factories\Filter;

use Illuminate\Support\Collection;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\Select\SelectFilterOption;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\Select\SelectFilterOptions;

final readonly class SelectFilterOptionsFactory
{
    /**
     * @param array<string, string> $options
     */
    public static function createFromArray(array $options): SelectFilterOptions
    {
        return new SelectFilterOptions((new Collection($options))->map(
            static fn (string $label, string $value) => new SelectFilterOption($value, $label)
        )->toArray());
    }
}