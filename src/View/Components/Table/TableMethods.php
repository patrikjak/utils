<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components\Table;

trait TableMethods
{
    public function showCheckboxes(): bool
    {
        return $this->table->showCheckboxes;
    }

    public function showOrder(): bool
    {
        return $this->table->showOrder;
    }

    public function hasActions(): bool
    {
        return $this->table->hasActions();
    }
}