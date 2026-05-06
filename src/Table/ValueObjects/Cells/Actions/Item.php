<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Cells\Actions;

use Closure;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Common\Icon;

readonly class Item
{
    public function __construct(
        public string $label,
        public string $classId,
        public ?Icon $icon = null,
        public Type $type = Type::NEUTRAL,
        public bool|Closure $visible = true,
        public string|Closure|null $href = null,
        public ?string $method = null,
        public bool $inline = false,
    ) {
    }
}
