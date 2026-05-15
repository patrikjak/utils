<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Filter\Criteria;

use Patrikjak\Utils\Table\Contracts\Filter\NeedsDatabaseColumn;
use Patrikjak\Utils\Table\Enums\Filter\FilterType;
use Patrikjak\Utils\Table\Enums\Filter\JsonFilterType;

readonly class JsonFilterCriteria extends AbstractFilterCriteria implements NeedsDatabaseColumn
{
    public function __construct(
        string $column,
        public ?string $jsonPath,
        public ?string $value,
        public JsonFilterType $filterType,
        public ?string $databaseColumn = null,
    ) {
        parent::__construct($column);
    }

    public function getDatabaseColumn(): ?string
    {
        return $this->databaseColumn;
    }

    public function withDatabaseColumn(string $databaseColumn): static
    {
        // @phpstan-ignore new.static
        return new static($this->column, $this->jsonPath, $this->value, $this->filterType, $databaseColumn);
    }

    public function getType(): string
    {
        return FilterType::Json->value;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'column' => $this->column,
            'jsonPath' => $this->jsonPath,
            'value' => $this->value,
            'operator' => $this->filterType->value,
            'type' => $this->getType(),
        ];
    }

    /**
     * @return array<string, string|null>
     */
    public function getFilterData(): array
    {
        return [
            'operator' => $this->filterType->value,
            'json-path' => $this->jsonPath,
            'value' => $this->value,
        ];
    }
}
