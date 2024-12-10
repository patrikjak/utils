<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Http\Middlewares;

use Closure;
use Illuminate\Config\Repository;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Patrikjak\Utils\Common\Exceptions\InvalidRecaptchaTokenException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Recaptcha
{
    private const string GOOGLE_RECAPTCHA_URL = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct(private readonly Repository $config, private readonly Factory $client)
    {
    }

    /**
     * @throws ConnectionException
     * @throws Throwable
     */
    public function handle(Request $request, Closure $next, ?string $exceptionClass = null): Response
    {
        $token = $request->input('recaptchaToken');
        $exceptionClass ??= InvalidRecaptchaTokenException::class;
        $exception = new $exceptionClass();

        if (!$exception instanceof Throwable) {
            throw new InvalidArgumentException('Exception class must be an instance of Throwable.');
        }

        if (!isset($token)) {
            throw $exception;
        }

        $response = $this->client->asForm()->post(self::GOOGLE_RECAPTCHA_URL, [
            'secret' => $this->config->get('pjutils.recaptcha_secret_key'),
            'response' => $token,
            'remoteip' => $request->ip(),
        ])->json();

        if (!$response['success']) {
            throw $exception;
        }

        return $next($request);
    }

    public static function withExceptionClass(string $exceptionClass): string
    {
        return sprintf('%s:%s', self::class, $exceptionClass);
    }
}
