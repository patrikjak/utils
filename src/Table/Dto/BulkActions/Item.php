<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\BulkActions;

use Patrikjak\Utils\Common\Enums\Icon;
use Patrikjak\Utils\Common\Enums\Type;

final readonly class Item
{
    public function __construct(
        public string $label,
        public string $action,
        public string $method = 'POST',
        public ?Icon $icon = null,
        public Type $type = Type::NEUTRAL,
    ) {
    }
}