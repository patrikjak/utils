<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Dto\Filter;

use Patrikjak\Utils\Common\Enums\Filter\FilterType;
use Patrikjak\Utils\Common\Enums\Filter\JsonFilterType;

class JsonFilterCriteria extends AbstractFilterCriteria
{
    public function __construct(
        string $column,
        public ?string $jsonPath,
        public ?string $value,
        public JsonFilterType $filterType,
    ) {
        parent::__construct($column);
    }

    public function getType(): FilterType
    {
        return FilterType::JSON;
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
            'type' => $this->getType()->value,
        ];
    }
}
