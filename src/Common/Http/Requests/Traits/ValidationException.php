<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Http\Requests\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException as BaseValidationException;

trait ValidationException
{
    /**
     * @throws BaseValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new BaseValidationException(
            $validator,
            new JsonResponse(['errors' => $validator->errors()->toArray()], 422),
        );
    }
}
