<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Table\Services\Implementations;

use Closure;
use Patrikjak\Utils\Table\Builder\Cell;
use Patrikjak\Utils\Table\Builder\TableBuilder;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Services\TableProvider as BaseTableProvider;

final class JsonFilterTableProvider extends BaseTableProvider
{
    private ?Closure $configurator = null;

    /**
     * @var array<string, string>
     */
    private array $filterColumnMap = [];

    public function configure(Closure $configurator): void
    {
        $this->configurator = $configurator;
    }

    /**
     * @param array<string, string> $map
     */
    public function setColumnMap(array $map): void
    {
        $this->filterColumnMap = $map;
    }

    /**
     * @return array<string, string>
     */
    protected function getColumnMap(): array
    {
        return $this->filterColumnMap;
    }

    /**
     * @param array<string, string> $columnMap
     */
    protected function build(?Parameters $parameters, array $columnMap): TableBuilder
    {
        $builder = TableBuilder::for('json-table', $this->getJsonTableData(), $columnMap)
            ->column('id', 'ID', static fn (array $row) => Cell::simple((string) $row['id']))
            ->column('name', 'Name', static fn (array $row) => Cell::simple((string) $row['name']))
            ->column('metadata', 'Metadata', static fn (array $row) => Cell::simple((string) $row['metadata']))
            ->column('data', 'Data', static fn (array $row) => Cell::simple((string) $row['data']))
            ->column('tags', 'Tags', static fn (array $row) => Cell::simple((string) $row['tags']))
            ->column('settings', 'Settings', static fn (array $row) => Cell::simple((string) $row['settings']))
            ->column('preferences', 'Preferences', static fn (array $row) => Cell::simple((string) $row['preferences']))
            ->column('contacts', 'Contacts', static fn (array $row) => Cell::simple((string) $row['contacts']))
            ->column('users', 'Users', static fn (array $row) => Cell::simple((string) $row['users']))
            ->column('matrix', 'Matrix', static fn (array $row) => Cell::simple((string) $row['matrix']))
            ->column('json_data', 'JSON Data', static fn (array $row) => Cell::simple((string) $row['json_data']))
            ->htmlPartsUrl('https://example.com/table');

        if ($this->configurator !== null) {
            ($this->configurator)($builder, $parameters);
        }

        return $builder;
    }

    /**
     * @return array<array<string, string>>
     */
    private function getJsonTableData(): array
    {
        return [
            [
                'id' => '1',
                'name' => 'John Doe',
                'metadata' => '{"email": "john@example.com", "phone": "+420123456789"}',
                'data' => '{"address": {"city": "Prague", "country": "CZ"}, "status": "active"}',
                'tags' => '{"items": ["tech", "admin", "user"]}',
                'settings' => '{"theme": "dark", "notifications": true}',
                'preferences' => '{"language": "en", "timezone": "Europe/Prague"}',
                'contacts' => '{"list": [{"phones": ["+420123456", "+420987654"]}]}',
                'users' => '{"data": [{"profile": {"name": "John"}}, {"profile": {"name": "Jane"}}]}',
                'matrix' => '{"values": [["a", "b"], ["c", "d"]]}',
                'json_data' => '{"search_value": "found", "other": "data"}',
            ],
            [
                'id' => '2',
                'name' => 'Jane Smith',
                'metadata' => '{"email": "jane@example.com", "phone": "+420987654321"}',
                'data' => '{"address": {"city": "Brno", "country": "CZ"}, "status": "inactive"}',
                'tags' => '{"items": ["user", "customer"]}',
                'settings' => '{"theme": "light", "notifications": false}',
                'preferences' => '{"language": "sk", "timezone": "Europe/Bratislava"}',
                'contacts' => '{"list": [{"phones": ["+421123456", "+421987654"]}]}',
                'users' => '{"data": [{"profile": {"name": "Alice"}}, {"profile": {"name": "Bob"}}]}',
                'matrix' => '{"values": [["1", "2"], ["3", "4"]]}',
                'json_data' => '{"config": "value", "search_value": "test"}',
            ],
            [
                'id' => '3',
                'name' => 'Admin User',
                'metadata' => '{"email": "admin@example.com", "phone": "+420555666777"}',
                'data' => '{"address": {"city": "Ostrava", "country": "CZ"}, "status": "active"}',
                'tags' => '{"items": ["admin", "tech", "support"]}',
                'settings' => '{"theme": "dark", "notifications": true}',
                'preferences' => '{"language": "en", "timezone": "UTC"}',
                'contacts' => '{"list": [{"phones": ["+420111222", "+420333444"]}]}',
                'users' => '{"data": [{"profile": {"name": "Charlie"}}, {"profile": {"name": "David"}}]}',
                'matrix' => '{"values": [["x", "y"], ["z", "w"]]}',
                'json_data' => '{"search_value": "admin_data", "type": "admin"}',
            ],
        ];
    }
}
