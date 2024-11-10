<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Form extends Component
{
    public readonly string $implodedDataAttributes;

    public readonly string $originalMethod;

    /**
     * @param array<string, string> $dataAttributes
     */
    public function __construct(
        public readonly string $action,
        public string $method = 'POST',
        public readonly ?string $actionLabel = 'Submit',
        public readonly ?string $redirect = null,
        public array $dataAttributes = [],
    ) {
        $this->originalMethod = $method;

        if (in_array($method, ['PUT', 'PATCH', 'DELETE'], true)) {
            $this->method = 'POST';
        }

        if ($redirect !== null) {
            $this->dataAttributes['redirect'] = $redirect;
        }

        $this->implodedDataAttributes = $this->resolveDataAttributes();
    }

    public function render(): View
    {
        return view('pjutils::components.form');
    }

    private function resolveDataAttributes(): string
    {
        return (new Collection($this->dataAttributes))
            ->map(static fn ($value, $key) => sprintf('data-%s=%s', $key, $value))
            ->implode(' ');
    }
}
