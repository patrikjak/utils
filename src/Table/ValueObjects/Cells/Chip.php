<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Cells;

use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Contracts\Cells\Cell as CellContract;

readonly class Chip extends Cell implements CellContract
{
    public function __construct(
        string $value,
        public Type $type,
        ?int $maxLength = null,
        bool $noTruncation = false,
    ) {
        parent::__construct($value, $maxLength, $noTruncation);
    }

    public function getType(): string
    {
        return 'chip';
    }
}
