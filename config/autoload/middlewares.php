<?php /** @formatter:off */
declare(strict_types=1);

return [
    'http'  =>  [
        App\Middleware\MiddlewareDomainCross::class,
        Hyperf\Validation\Middleware\ValidationMiddleware::class,
    ],
];