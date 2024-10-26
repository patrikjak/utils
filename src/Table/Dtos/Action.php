<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dtos;

use Patrikjak\Utils\Table\Enums\Actions\Type;

final readonly class Action
{
    public function __construct(
        public string $label,
        public string $classId,
        public ?string $icon = null,
        public Type $type = Type::DEFAULT,
    ) {
    }
}