<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Cells\Actions;

use Closure;
use Patrikjak\Utils\Common\Enums\Icon;
use Patrikjak\Utils\Common\Enums\Type;

final readonly class Item
{
    public function __construct(
        public string $label,
        public string $classId,
        public Icon|string|null $icon = null,
        public Type $type = Type::NEUTRAL,
        public bool|Closure $visible = true,
        public string|Closure|null $href = null,
        public ?string $method = null,
    ) {
    }
}