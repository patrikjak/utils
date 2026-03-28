<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Dto;

final readonly class EmptyState
{
    public function __construct(
        public string $title,
        public ?string $description = null,
        public ?string $icon = null,
    ) {
    }
}
