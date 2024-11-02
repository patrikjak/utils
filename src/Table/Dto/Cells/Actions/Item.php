<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Cells\Actions;

use Patrikjak\Utils\Common\Enums\Type;

final readonly class Item
{
    public function __construct(
        public string $label,
        public string $classId,
        public ?string $icon = null,
        public Type $type = Type::NEUTRAL,
    ) {
    }
}