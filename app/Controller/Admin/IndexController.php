<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Abstract\AbstractControllerHttp;
use App\Request\Admin\RequestAdminAdministrator;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Validation\Annotation\Scene;

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
     * @return array
     * @api /admin/index/login
     */
    #[
        RequestMapping(path: 'login', methods: ['GET', 'POST']),
        Scene,
    ]
    public function login(RequestAdminAdministrator $request): array
    {
        $request->validated();
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return [
            'method' => $method,
            'message' => "Hello $user.",
        ];
    }
}
