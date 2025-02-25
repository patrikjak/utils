<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Filter\Definitions;

interface RangeData
{
    public function getMin(): ?string;

    public function getMax(): ?string;
}