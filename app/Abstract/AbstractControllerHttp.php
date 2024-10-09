<?php
declare(strict_types=1);

namespace App\Abstract;

use App\Utils\Response\ResponseUtil;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

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
     * @var ResponseUtil $response
     */
    #[Inject]
    protected ResponseUtil $response;
}