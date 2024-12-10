<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class InvalidRecaptchaTokenException extends Exception
{
    public function render(): JsonResponse
    {
        return new JsonResponse(['errors' => ['email' => __('pjutils::validation.recaptcha')]], 422);
    }
}
