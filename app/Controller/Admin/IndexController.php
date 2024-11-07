<?php
declare(strict_types=1);

namespace app\Controller\Admin;

use App\Abstract\Controller\AbstractAdminController;
use App\Middleware\Admin\MiddlewareAdminAuthentication;
use App\Model\Admin\ModelAdminAdministrator;
use App\Request\Admin\RequestAdminAdministrator;
use App\Service\Admin\ServiceAdminPermissions;
use Hyperf\Context\Context;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Validation\Annotation\Scene;
use Psr\Http\Message\ResponseInterface;

/**
 * @IndexController
 * @\App\Controller\Admin\IndexController
 */
#[
    Controller(prefix: '/admin/index'),
]
class IndexController extends AbstractAdminController
{

    #[Inject]
    protected ServiceAdminPermissions $serviceAdminPermissions;

    /**
     * @param RequestAdminAdministrator $request
     * @return ResponseInterface
     * @api /admin/index/login
     */
    #[
        RequestMapping(path: 'login', methods: ['POST']),
        Scene,
    ]
    public function login(RequestAdminAdministrator $request): ResponseInterface
    {
        $inputs = $request->validated();

        $token = $this->serviceAdminPermissions->login(... $inputs);

        if ($token === false) {
            return $this->response->error('账号或密码错误');
        }

        return $this->response->auth($token, message: '登录成功');
    }

    /**
     * @return ResponseInterface
     * @api /admin/index/routes
     */
    #[
        Middlewares([
            MiddlewareAdminAuthentication::class,
        ]),
        RequestMapping(path: 'routes', methods: ['GET', 'POST']),
        Scene,
    ]
    public function router(): ResponseInterface
    {
        /**
         * @var ModelAdminAdministrator $userinfo
         */
        $userinfo = Context::get('userinfo');

        $router = $this->serviceAdminPermissions->getRoutes($userinfo->roleId);

        return $this->response->success($router);
    }
}
