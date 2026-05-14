<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\View;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Table;
use Patrikjak\Utils\Table\Registry\CellRegistry;
use Patrikjak\Utils\Table\ValueObjects\Cells\Actions\Item;
use Patrikjak\Utils\Table\ValueObjects\Cells\Cell;
use Patrikjak\Utils\Table\View\Traits\TableMethods;
use stdClass;

class Row extends Component
{
    use TableMethods;

    public readonly string $rowId;

    public readonly ?string $rowClass;

    public ?string $hiddenActions = null;

    public bool $allActionsAreHidden = false;

    public ?string $actionsDataAttributes = null;

    /**
     * @var array<Item>
     */
    public array $inlineActions = [];

    /**
     * @var array<Item>
     */
    public array $dropdownActions = [];

    public bool $hasDropdownActions = false;

    /**
     * @var array<string>
     */
    public array $hiddenInlineActionIds = [];

    /**
     * @var array<string, string|null>
     */
    public array $inlineActionHrefs = [];

    /**
     * @param array<string, scalar|array<string>> $row
     */
    public mixed $rawRow;

    /**
     * @param array<string, scalar|array<string>> $row
     */
    public function __construct(
        public Table $table,
        public array $row,
        public stdClass $loop,
        private readonly CellRegistry $cellRegistry,
    ) {
        $this->rowId = $this->resolveRowId();
        $this->rowClass = isset($row['rowClass']) ? implode(' ', $row['rowClass']) : null;
        $this->rawRow = $table->rawData->get($this->rowId) ?? $row;
    }

    public function render(): View
    {
        $this->splitActions();
        $this->setHiddenActions();
        $this->setHiddenInlineActions();
        $this->setActionsDataAttributes();
        $this->resolveInlineActionHrefs();

        return view('pjutils::table.row');
    }

    public function getCellView(Cell $cell): string
    {
        return $this->cellRegistry->getView($cell->getType());
    }

    private function resolveRowId(): string
    {
        return (string) $this->row[$this->table->rowId];
    }

    private function splitActions(): void
    {
        foreach ($this->table->actions as $action) {
            if ($action->inline) {
                $this->inlineActions[] = $action;
            } else {
                $this->dropdownActions[] = $action;
            }
        }

        $this->hasDropdownActions = $this->dropdownActions !== [];
    }

    private function setHiddenInlineActions(): void
    {
        foreach ($this->inlineActions as $action) {
            if ($action->visible === false) {
                $this->hiddenInlineActionIds[] = $action->classId;

                continue;
            }

            if (!$action->visible instanceof Closure) {
                continue;
            }

            if (!call_user_func($action->visible, $this->rawRow)) {
                $this->hiddenInlineActionIds[] = $action->classId;
            }
        }
    }

    private function setHiddenActions(): void
    {
        $dropdownActions = $this->dropdownActions;
        $hiddenActions = [];

        foreach ($dropdownActions as $action) {
            if ($action->visible === false) {
                $hiddenActions[] = $action->classId;

                continue;
            }

            if (!$action->visible instanceof Closure) {
                continue;
            }

            if (call_user_func($action->visible, $this->rawRow)) {
                continue;
            }

            $hiddenActions[] = $action->classId;
        }

        $this->hiddenActions = $hiddenActions === [] ? null : implode(',', $hiddenActions);
        $this->allActionsAreHidden = $this->hasDropdownActions
            && count($hiddenActions) === count($dropdownActions);
    }

    private function resolveInlineActionHrefs(): void
    {
        foreach ($this->inlineActions as $action) {
            if ($action->href instanceof Closure) {
                $this->inlineActionHrefs[$action->classId] = call_user_func($action->href, $this->rawRow);
            } else {
                $this->inlineActionHrefs[$action->classId] = $action->href;
            }
        }
    }

    private function setActionsDataAttributes(): void
    {
        $actions = $this->table->actions;
        $dataAttributes = [];

        foreach ($actions as $action) {
            if (is_string($action->href)) {
                $dataAttributes[] = sprintf('data-%s-href="%s"', $action->classId, $action->href);
            }

            if ($action->href instanceof Closure) {
                $dataAttributes[] = sprintf(
                    'data-%s-href="%s"',
                    $action->classId,
                    call_user_func($action->href, $this->rawRow),
                );
            }

            if ($action->method !== null) {
                $dataAttributes[] = sprintf('data-%s-method="%s"', $action->classId, $action->method);
            }
        }

        $this->actionsDataAttributes = $dataAttributes === [] ? null : implode(' ', $dataAttributes);
    }
}
