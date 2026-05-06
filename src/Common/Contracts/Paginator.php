<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Contracts;

use Illuminate\Support\Collection;

interface Paginator
{
    public function getPage(): int;

    public function getPageSize(): int;

    public function getData(): Collection;
}
