<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\View\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Common\Enums\Icon;

class Input extends Component
{
    public readonly string $wrapperClass;

    /**
     * @var array<string, string>
     */
    public readonly array $inputAttributes;

    public function __construct(
        public readonly string $name,
        public readonly string $type = 'text',
        public readonly ?string $label = null,
        public readonly ?string $autocomplete = null,
        public readonly ?string $placeholder = null,
        public readonly ?string $error = null,
        public readonly bool $required = false,
        public ?Icon $icon = null,
    ) {
        if (isset($this->error)) {
            $this->icon = Icon::CIRCLE_EXCLAMATION;
        }

        $this->wrapperClass = $this->resolveWrapperClass();
        $this->inputAttributes = $this->resolveInputAttributes();
    }

    public function render(): View
    {
        return view('pjutils::components.form.input');
    }

    public function resolveWrapperClass(): string
    {
        $classes = ['pj-classic'];

        if (isset($this->icon)) {
            $classes[] = 'iconic';
        }

        if (isset($this->error)) {
            $classes[] = 'error';
        }

        if ($this->required) {
            $classes[] = 'required';
        }

        return implode(' ', $classes);
    }

    /**
     * @return array<string, string>
     */
    private function resolveInputAttributes(): array
    {
        $attributes = [
            'id' => $this->name,
            'name' => $this->name,
            'type' => $this->type,
        ];

        if ($this->autocomplete !== null && $this->autocomplete !== '') {
            $attributes['autocomplete'] = $this->autocomplete;
        }

        if ($this->placeholder !== null && $this->placeholder !== '') {
            $attributes['placeholder'] = $this->placeholder;
        }

        return $attributes;
    }
}
