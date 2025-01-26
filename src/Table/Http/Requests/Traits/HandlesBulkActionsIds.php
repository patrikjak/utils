<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Http\Requests\Traits;

use Illuminate\Support\Collection;

trait HandlesBulkActionsIds
{
    public function getBulkActionsIds(): Collection
    {
        return $this->collect('bulkActionsIds', []);
    }
}