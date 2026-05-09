<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Builder;

use Closure;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Common\Icon;

final readonly class ActionDefinition
{
    public function __construct(
        public string $label,
        public string $classId,
        public ?Icon $icon,
        public Closure $href,
        public Type $type = Type::NEUTRAL,
        public string $method = 'GET',
        public ?Closure $when = null,
        public ?Closure $whenNot = null,
        public bool $inline = false,
    ) {
    }
}
