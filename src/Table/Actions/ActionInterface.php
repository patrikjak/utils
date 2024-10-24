<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Actions;

interface ActionInterface
{
    public function getLabel(): string;

    public function getIcon(): ?string;

    public function getType(): Type;

    public function getClassId(): string;
}