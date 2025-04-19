<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Cells\Actions;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Common\Enums\Icon;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item as ActionItem;

class Item extends Component
{
    public readonly string $actionItemClass;

    public Icon|string|null $icon = null;

    public function __construct(public readonly ActionItem $action)
    {
        $this->actionItemClass = $this->resolveClass();

        if ($this->hasIcon()) {
            $this->icon = $this->action->icon;
        }
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

    private function hasIcon(): bool
    {
        return $this->action->icon !== null;
    }
}
