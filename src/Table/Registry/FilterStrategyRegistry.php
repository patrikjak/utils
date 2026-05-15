<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Registry;

use Patrikjak\Utils\Common\Services\QueryBuilder\Filters\Filter;
use Patrikjak\Utils\Table\Contracts\Filter\FilterCriteriaFactory;
use Patrikjak\Utils\Table\Exceptions\UnregisteredFilterStrategyException;

final class FilterStrategyRegistry
{
    /**
     * @var array<string, Filter>
     */
    private array $strategies = [];

    /**
     * @var array<string, FilterCriteriaFactory>
     */
    private array $criteriaFactories = [];

    /**
     * @var array<string, string>
     */
    private array $formViews = [];

    /**
     * @var array<string, string>
     */
    private array $chipViews = [];

    public function register(
        string $type,
        Filter $strategy,
        FilterCriteriaFactory $criteriaFactory,
        ?string $formView = null,
        ?string $chipView = null,
    ): void {
        $this->strategies[$type] = $strategy;
        $this->criteriaFactories[$type] = $criteriaFactory;

        if ($formView !== null) {
            $this->formViews[$type] = $formView;
        }

        if ($chipView !== null) {
            $this->chipViews[$type] = $chipView;
        }
    }

    public function has(string $type): bool
    {
        return isset($this->strategies[$type]);
    }

    public function get(string $type): Filter
    {
        if (!isset($this->strategies[$type])) {
            throw UnregisteredFilterStrategyException::forType($type);
        }

        return $this->strategies[$type];
    }

    public function getCriteriaFactory(string $type): FilterCriteriaFactory
    {
        if (!isset($this->criteriaFactories[$type])) {
            throw UnregisteredFilterStrategyException::forType($type);
        }

        return $this->criteriaFactories[$type];
    }

    public function getFormView(string $type): string
    {
        if (!isset($this->formViews[$type])) {
            throw UnregisteredFilterStrategyException::forType($type);
        }

        return $this->formViews[$type];
    }

    public function getChipView(string $type): ?string
    {
        return $this->chipViews[$type] ?? null;
    }
}
