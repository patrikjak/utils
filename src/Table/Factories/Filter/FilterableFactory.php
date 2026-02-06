<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Factories\Filter;

use Carbon\CarbonInterface;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\Date\DateFilterDefinition;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\FilterDefinition;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\Number\NumberFilterDefinition;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\Select\SelectFilterDefinition;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\Text\TextFilterDefinition;

final readonly class FilterableFactory
{
    public static function text(): FilterDefinition
    {
        return new TextFilterDefinition();
    }

    public static function select(string $dataProviderUrl): FilterDefinition
    {
        return new SelectFilterDefinition($dataProviderUrl);
    }

    public static function date(?CarbonInterface $from = null, ?CarbonInterface $to = null): FilterDefinition
    {
        return new DateFilterDefinition($from, $to);
    }

    public static function number(?int $min = null, ?int $max = null): FilterDefinition
    {
        return new NumberFilterDefinition($min, $max);
    }
}