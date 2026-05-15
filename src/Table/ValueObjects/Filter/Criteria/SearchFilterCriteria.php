<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Filter\Criteria;

use Patrikjak\Utils\Table\Enums\Filter\TextFilterType;

readonly class SearchFilterCriteria extends TextFilterCriteria
{
    public const string COLUMN = '__search';

    /**
     * @param array<string> $searchableColumns real database column names to fan out across
     */
    public function __construct(string $value, public array $searchableColumns)
    {
        parent::__construct(self::COLUMN, $value, TextFilterType::CONTAINS);
    }
}
