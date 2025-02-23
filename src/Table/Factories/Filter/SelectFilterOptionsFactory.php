<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Factories\Filter;

use Patrikjak\Utils\Table\Dto\Filter\Definitions\Select\SelectFilterOption;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\Select\SelectFilterOptions;

final readonly class SelectFilterOptionsFactory
{
    /**
     * @param array<string, string> $options
     */
    public static function createFromArray(array $options): SelectFilterOptions
    {
        return new SelectFilterOptions(array_map(
            static fn (string $value, string $label) => new SelectFilterOption($value, $label),
            array_keys($options),
            $options,
        ));
    }
}