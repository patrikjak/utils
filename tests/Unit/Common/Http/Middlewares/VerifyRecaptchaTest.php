<?php

namespace Patrikjak\Utils\Tests\Unit\Common\Http\Middlewares;

use Exception;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Mockery;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;
use Patrikjak\Utils\Common\Exceptions\InvalidRecaptchaTokenException;
use Patrikjak\Utils\Common\Http\Middlewares\VerifyRecaptcha;
use Symfony\Component\HttpFoundation\Response;

class VerifyRecaptchaTest extends TestCase
{
    public function testSuccessfulHandleWithDefaultException(): void
    {
        $request = Request::create('/test', 'POST', ['recaptchaToken' => 'test']);
        $this->mockRecaptchaCall();

        $response = $this->getMiddlewareResponse($request);

        $this->assertEquals('OK', $response->getContent());
    }

    public function testFailedHandleWithDefaultException(): void
    {
        $request = Request::create('/test', 'POST', ['recaptchaToken' => 'test']);
        $this->mockRecaptchaCall(false);

        $this->expectException(InvalidRecaptchaTokenException::class);

        $this->getMiddlewareResponse($request);
    }

    public function testFailedHandleWithMissingRecaptchaToken(): void
    {
        $request = Request::create('/test', 'POST');

        $this->expectException(InvalidRecaptchaTokenException::class);

        $this->getMiddlewareResponse($request);
    }

    public function testCustomExceptionParameter(): void
    {
        $this->assertEquals(sprintf('%s:%s', VerifyRecaptcha::class, Exception::class), VerifyRecaptcha::withExceptionClass(Exception::class));
    }

    public function testFailedHandleWithCustomException(): void
    {
        $request = Request::create('/test', 'POST', ['recaptchaToken' => 'test']);
        $this->mockRecaptchaCall(false);

        $this->expectException(Exception::class);

        $this->getMiddleware()->handle($request, function ($request) {
            return response('OK');
        }, Exception::class);
    }

    public function testFailedHandleWithUnsupportedException(): void
    {
        $request = Request::create('/test', 'POST', ['recaptchaToken' => 'test']);

        $this->expectException(InvalidArgumentException::class);

        $this->getMiddleware()->handle($request, function ($request) {
            return response('OK');
        }, (new class {})::class);
    }

    private function getMiddleware(): VerifyRecaptcha
    {
        $recaptchaMiddleware = app()->make(VerifyRecaptcha::class);
        assert($recaptchaMiddleware instanceof VerifyRecaptcha);

        return $recaptchaMiddleware;
    }

    private function mockRecaptchaCall(bool $successful = true): void
    {
        $this->instance(Factory::class, Mockery::mock(
            Factory::class,
            function (MockInterface $mock) use ($successful) {
                $mock->shouldReceive('asForm')->andReturnSelf();
                $mock->shouldReceive('post')->andReturnSelf();
                $mock->shouldReceive('json')->andReturn(['success' => $successful]);
            })
        );
    }

    private function getMiddlewareResponse(Request $request): Response
    {
        return $this->getMiddleware()->handle($request, function ($request) {
            return response('OK');
        });
    }
}