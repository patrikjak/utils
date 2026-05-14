<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\View\Filter;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Enums\Filter\JsonFilterType;
use Patrikjak\Utils\Table\Enums\Filter\TextFilterType;
use Patrikjak\Utils\Table\Registry\FilterStrategyRegistry;

class FilterForm extends Component
{
    /**
     * @var array<string, string>
     */
    public array $textFilterTypes = [];

    /**
     * @var array<string, string>
     */
    public array $jsonFilterTypes = [];

    public string $formView;

    public function __construct(
        FilterStrategyRegistry $registry,
        public string $type,
        public ?string $min = null,
        public ?string $max = null,
        public ?string $jsonPath = null,
        public ?string $optionsUrl = null,
    ) {
        $this->formView = $registry->getFormView($type);

        $this->textFilterTypes = new Collection(TextFilterType::cases())->flatMap(
            static fn (TextFilterType $type) => [$type->value => $type->toLabel()],
        )->toArray();

        $this->jsonFilterTypes = new Collection(JsonFilterType::cases())->flatMap(
            static fn (JsonFilterType $type) => [$type->value => $type->toLabel()],
        )->toArray();
    }

    public function render(): View
    {
        return $this->view('pjutils::table.filter.filter-form');
    }
}
