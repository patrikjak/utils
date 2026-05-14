<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Filter\Criteria;

use Patrikjak\Utils\Table\Enums\Filter\FilterType;
use Patrikjak\Utils\Table\Enums\Filter\TextFilterType;

readonly class TextFilterCriteria extends AbstractFilterCriteria
{
    public function __construct(
        string $column,
        public ?string $value,
        public TextFilterType $filterType,
    ) {
        parent::__construct($column);
    }

    public function getType(): string
    {
        return FilterType::Text->value;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            'column' => $this->column,
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
            'value' => $this->value,
        ];
    }
}
