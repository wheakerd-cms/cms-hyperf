<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Abstract\AbstractControllerHttp;
use App\Request\Admin\RequestAdminAdministrator;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Validation\Annotation\Scene;
use Psr\Http\Message\ResponseInterface;

/**
 * @IndexController
 * @\App\Controller\IndexController
 */
#[
    Controller(prefix: '/admin/index', server: 'http'),
]
class IndexController extends AbstractControllerHttp
{

    /**
     * @param RequestAdminAdministrator $request
     * @return ResponseInterface
     * @api /admin/index/login
     */
    #[
        RequestMapping(path: 'login', methods: ['GET', 'POST']),
        Scene,
    ]
    public function login(RequestAdminAdministrator $request): ResponseInterface
    {
        $request->validated();

        return $this->response->auth('1234567890', [], '11');
//        return $this->response->success();
    }
}
