<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Unit\Common\Exceptions;

use Orchestra\Testbench\TestCase;
use Patrikjak\Utils\Common\Exceptions\InvalidRecaptchaTokenException;

class InvalidRecaptchaTokenExceptionTest extends TestCase
{
    public function testRenderException(): void
    {
        $exception = new InvalidRecaptchaTokenException();
        $response = $exception->render();

        $this->assertEquals(
            json_encode([
                'errors' => [
                    'email' => 'pjutils::validation.recaptcha',
                ],
            ]),
            $response->getContent(),
        );

        $this->assertEquals(422, $response->getStatusCode());
    }
}