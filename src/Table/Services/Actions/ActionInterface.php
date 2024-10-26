<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Services\Actions;

use Patrikjak\Utils\Table\Enums\Actions\Type;

interface ActionInterface
{
    public function getLabel(): string;

    public function getIcon(): ?string;

    public function getType(): Type;

    public function getClassId(): string;
}