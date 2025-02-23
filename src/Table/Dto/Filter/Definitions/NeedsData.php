<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Filter\Definitions;

interface NeedsData
{
    public function getDataUrl(): string;
}