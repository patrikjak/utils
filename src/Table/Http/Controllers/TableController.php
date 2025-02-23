<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\Compilers\BladeCompiler;
use Patrikjak\Utils\Common\Enums\Filter\FilterType;
use Patrikjak\Utils\Table\View\Filter\FilterForm;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TableController
{
    /**
     * @throws Throwable
     */
    public function form(string $type, BladeCompiler $bladeCompiler): JsonResponse
    {
        $filterType = FilterType::tryFrom($type);

        if ($filterType === null) {
            return new JsonResponse(status: Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'modal' => $bladeCompiler::renderComponent(new FilterForm($filterType)),
        ]);
    }
}
