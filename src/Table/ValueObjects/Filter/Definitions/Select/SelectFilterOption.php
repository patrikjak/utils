<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\Select;

readonly class SelectFilterOption
{
    public function __construct(public string $value, public string $label)
    {
    }
}
