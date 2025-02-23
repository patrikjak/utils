<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Filter\Definitions\Select;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Common\View\Dropdown;

final readonly class SelectFilterOptions implements Arrayable
{
    /**
     * @param array<SelectFilterOption> $options
     */
    public function __construct(public array $options)
    {
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        $options = (new Collection($this->options))->flatMap(
            static fn (SelectFilterOption $option) => [$option->value => $option->label],
        )->toArray();

        return [
            'options' => $options,
            'htmlComponent' => Blade::renderComponent(new Dropdown(
                $options,
                array_key_first($options),
                __('pjutils::table.filter_available_options'),
            )),
        ];
    }
}