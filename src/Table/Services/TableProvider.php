<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Table\Builder\TableBuilder;
use Patrikjak\Utils\Table\Dto\Filter\Settings as FilterSettings;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Dto\Table;
use Patrikjak\Utils\Table\Exceptions\InvalidTableBuilderException;
use Patrikjak\Utils\Table\View\Body;
use Patrikjak\Utils\Table\View\Head;
use Patrikjak\Utils\Table\View\Options;
use Patrikjak\Utils\Table\View\Pagination\Paginator;

abstract class TableProvider
{
    abstract protected function build(?Parameters $parameters): TableBuilder;

    /**
     * @throws BindingResolutionException
     * @throws InvalidTableBuilderException
     */
    public function getTable(?Parameters $parameters = null): Table
    {
        $enrichedParameters = $this->preEnrichParameters($parameters);

        return $this->build($enrichedParameters)->assembleTable($enrichedParameters);
    }

    /**
     * Override to declare display-column → real-database-column mappings.
     * Used to pre-enrich filter criteria before build() runs, and can be
     * called directly inside build() when passing the mask to applySort/applyFilter.
     *
     * @return array<string, string>
     */
    protected function getColumnMap(): array
    {
        return [];
    }

    private function preEnrichParameters(?Parameters $parameters): ?Parameters
    {
        if ($parameters === null) {
            return null;
        }

        $mask = $this->getColumnMap();

        if ($mask === []) {
            return $parameters;
        }

        if ($parameters->filterCriteria !== null) {
            $parameters = $parameters->withFilterCriteria(
                FilterSettings::enrichCriteria($parameters->filterCriteria, $mask),
            );
        }

        return $parameters;
    }

    /**
     * @return array<string, string|null>
     * @throws BindingResolutionException
     * @throws InvalidTableBuilderException
     */
    public function getHtmlParts(Parameters $parameters): array
    {
        $table = $this->getTable($parameters);

        $parts = [
            'head' => Blade::renderComponent(new Head($table)),
            'body' => Blade::renderComponent(new Body($table)),
            'options' => null,
        ];

        if ($table->isFilterable() || $table->isSearchable()) {
            $parts['options'] = Blade::renderComponent(new Options($table));
        }

        if ($table->hasPagination()) {
            $parts['pagination'] = Blade::renderComponent(
                new Paginator($table->paginationSettings),
            );
        }

        return $parts;
    }
}
