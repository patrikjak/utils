<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Contracts\Filter;

interface NeedsData
{
    public function getDataUrl(): string;
}
