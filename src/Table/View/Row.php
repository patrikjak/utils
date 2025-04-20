<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Cells\Cell;
use Patrikjak\Utils\Table\Dto\Table;
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
     * @param array<string, scalar|array<string>> $row
     */
    public function __construct(public Table $table, public array $row, public stdClass $loop)
    {
        $this->rowId = $this->resolveRowId();
        $this->rowClass = isset($row['rowClass']) ? implode(' ', $row['rowClass']) : null;
    }

    public function render(): View
    {
        $this->setHiddenActions();
        $this->setActionsDataAttributes();

        return view('pjutils::table.row');
    }

    public function getCellView(Cell $cell): string
    {
        return sprintf('pjutils.table::cells.%s', $cell->getType()->value);
    }

    private function resolveRowId(): string
    {
        return (string) $this->row[$this->table->rowId];
    }

    private function setHiddenActions(): void
    {
        $actions = $this->table->actions;
        $hiddenActions = [];

        foreach ($actions as $action) {
            if ($action->visible === false) {
                $hiddenActions[] = $action->classId;

                continue;
            }

            if (!$action->visible instanceof Closure) {
                continue;
            }

            if (call_user_func($action->visible, $this->row)) {
                continue;
            }

            $hiddenActions[] = $action->classId;
        }

        $this->hiddenActions = count($hiddenActions) === 0 ? null : implode(',', $hiddenActions);
        $this->allActionsAreHidden = count($hiddenActions) === count($actions);
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
                    call_user_func($action->href, $this->row),
                );
            }

            if ($action->method !== null) {
                $dataAttributes[] = sprintf('data-%s-method="%s"', $action->classId, $action->method);
            }
        }

        $this->actionsDataAttributes = count($dataAttributes) === 0 ? null : implode(' ', $dataAttributes);
    }
}
