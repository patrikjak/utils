<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\Compilers\BladeCompiler;
use Patrikjak\Utils\Table\Registry\FilterStrategyRegistry;
use Patrikjak\Utils\Table\View\Filter\FilterForm;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TableController
{
    public function __construct(private readonly FilterStrategyRegistry $filterStrategyRegistry)
    {
    }

    /**
     * @throws Throwable
     */
    public function form(string $type, BladeCompiler $bladeCompiler, Request $request): JsonResponse
    {
        if (!$this->filterStrategyRegistry->has($type)) {
            return new JsonResponse(status: Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'modal' => $bladeCompiler::renderComponent(new FilterForm(
                $this->filterStrategyRegistry,
                $type,
                $request->input('from'),
                $request->input('to'),
                $request->input('json-path'),
                $request->input('options-url'),
            )),
        ]);
    }
}
