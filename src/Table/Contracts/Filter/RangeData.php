<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Contracts\Filter;

interface RangeData
{
    public function getMin(): ?string;

    public function getMax(): ?string;
}
