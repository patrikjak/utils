<?php

namespace Patrikjak\Utils\Common\Http\Requests\Traits;

use Illuminate\Support\Collection;

trait HandlesBulkActionsIds
{
    public function getBulkActionsIds(): Collection
    {
        return $this->collect('bulkActionsIds', []);
    }
}