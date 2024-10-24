<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Actions;

final readonly class Action implements ActionInterface
{
    public function __construct(
        private string $label,
        private string $classId,
        private ?string $icon = null,
        private Type $type = Type::DEFAULT,
    ) {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getClassId(): string
    {
        return $this->classId;
    }
}