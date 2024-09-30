<?php
declare(strict_types=1);

namespace App\Abstract;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

/**
 * @AbstractControllerHttp
 * @\App\Abstract\AbstractControllerHttp
 */
abstract class AbstractControllerHttp
{
    /**
     * @var RequestInterface $request
     */
    #[Inject]
    protected RequestInterface $request;

    /**
     * @var ResponseInterface $response
     */
    #[Inject]
    protected ResponseInterface $response;
}