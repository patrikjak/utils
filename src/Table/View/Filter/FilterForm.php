<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Filter;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Patrikjak\Utils\Common\Enums\Filter\FilterType;
use Patrikjak\Utils\Common\Enums\Filter\TextFilterType;

class FilterForm extends Component
{
    /**
     * @var array<string, string>
     */
    public array $textFilterTypes = [];

    public function __construct(public FilterType $type)
    {
        $this->textFilterTypes = (new Collection(TextFilterType::cases()))->flatMap(
            static fn (TextFilterType $type) => [$type->value => $type->toLabel()],
        )->toArray();
    }

    public function render(): View
    {
        return $this->view('pjutils::table.filter.filter-form');
    }
}
