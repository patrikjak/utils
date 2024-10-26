<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components\Table;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dtos\Action;

class ActionItem extends Component
{
    public readonly string $actionItemClass;

    public readonly bool $hasIcon;

    public function __construct(public readonly Action $action)
    {
        $this->actionItemClass = $this->resolveClass();
        $this->hasIcon = $this->action->icon !== null;
    }

    public function render(): View
    {
        return view('pjutils::components.table.action-item');
    }

    private function resolveClass(): string
    {
        return implode(' ', ['action', $this->action->classId, $this->action->type->value]);
    }
}
