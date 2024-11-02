<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Cells\Actions;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item as ActionItem;

class Item extends Component
{
    public readonly string $actionItemClass;

    public readonly bool $hasIcon;

    public function __construct(public readonly ActionItem $action)
    {
        $this->actionItemClass = $this->resolveClass();
        $this->hasIcon = $this->action->icon !== null;
    }

    public function render(): View
    {
        return view('pjutils::table.cells.actions.item');
    }

    private function resolveClass(): string
    {
        return implode(
            ' ',
            [
                'action',
                $this->action->classId,
                $this->action->type->value,
            ],
        );
    }
}
