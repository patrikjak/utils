<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Table\Services\Implementations;

use Closure;
use Patrikjak\Utils\Table\Builder\Cell;
use Patrikjak\Utils\Table\Builder\TableBuilder;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Services\TableProvider as BaseTableProvider;

final class TableProvider extends BaseTableProvider
{
    use TableProviderData;

    private ?Closure $configurator = null;

    public function configure(Closure $configurator): void
    {
        $this->configurator = $configurator;
    }

    protected function build(?Parameters $parameters): TableBuilder
    {
        $builder = TableBuilder::for('table', $this->getRawTableData())
            ->column('id', 'ID', static fn (array $row) => Cell::simple($row['id']))
            ->column('name', 'Name', static fn (array $row) => Cell::simple($row['name']))
            ->column('email', 'Email', static fn (array $row) => Cell::simple($row['email']))
            ->column('link', 'Link', static fn (array $row) => Cell::link($row['link_label'], $row['link_href']))
            ->column('created_at', 'Created at', static fn (array $row) => Cell::simple($row['created_at']))
            ->column('updated_at', 'Updated at', static fn (array $row) => Cell::simple($row['updated_at']));

        if ($this->configurator !== null) {
            ($this->configurator)($builder, $parameters);
        }

        return $builder;
    }
}
