<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Table\Builder\TableBuilder;
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
        return $this->build($parameters)->assembleTable($parameters);
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
