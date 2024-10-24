<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components\Table;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Table;

final class Head extends Component
{
    use TableMethods;

    /**
     * @var array<string, string>
     */
    public readonly array $headerData;

    public function __construct(public Table $table)
    {
        $this->headerData = $this->getHeaderData();
    }

    public function render(): View
    {
        return view('pjutils::components.table.head');
    }

    /**
     * @return array<string, string>
     */
    private function getHeaderData(): array
    {
        $header = [];
        $headerData = $this->table->header;

        foreach ($this->table->columns as $dataKey => $type) {
            $header[$dataKey] = $headerData[$dataKey];
        }

        return $header;
    }
}
