<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Filter\Definitions\Select;

final readonly class SelectFilterOption
{
    public function __construct(public string $value, public string $label)
    {
    }
}