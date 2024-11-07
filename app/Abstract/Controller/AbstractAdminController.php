<?php
declare(strict_types=1);

namespace App\Abstract\Controller;

use App\Contract\Response;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

abstract class AbstractAdminController
{

    #[Inject]
    protected RequestInterface $request;

    #[Inject(value: Response::class)]
    protected Response $response;
}