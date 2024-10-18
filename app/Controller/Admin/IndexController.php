<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Abstract\AbstractControllerHttp;
use App\Dao\Admin\DaoAdministrator;
use App\Dao\Admin\DaoAdminRoles;
use App\Dao\Admin\DaoAdminRouter;
use App\Model\Admin\ModelAdminAdministrator;
use App\Request\Admin\RequestAdminAdministrator;
use App\Security\Admin\SecurityAdminJws;
use App\Service\Admin\ServiceAdminRoles;
use App\Service\Admin\ServiceAdminRouter;
use Hyperf\Context\Context;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Validation\Annotation\Scene;
use Psr\Http\Message\ResponseInterface;

/**
 * @IndexController
 * @\App\Controller\IndexController
 */
#[
    Controller(prefix: '/admin/index'),
]
class IndexController extends AbstractControllerHttp
{

    #[Inject]
    protected DaoAdministrator $daoAdministrator;
    #[Inject]
    protected DaoAdminRouter $daoAdminRouter;
    #[Inject]
    protected DaoAdminRoles $daoAdminRoles;
    #[Inject]
    protected ServiceAdminRoles $serviceAdminRoles;
    #[Inject]
    protected SecurityAdminJws $securityAdminJws;

    #[Inject]
    protected ServiceAdminRouter $serviceAdminRouter;

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

        $data = $this->daoAdministrator->findOne([
            'username' => $inputs ['username'],
        ]);

        if (!$data || !password_verify($inputs ['password'], $data ['password'])) {
            return $this->response->error('账号或者密码错误!');
        }

        if ($data ['status'] === false) {
            return $this->response->error('账号或者密码错误!');
        }

        $token = $this->securityAdminJws->create(['id' => $data ['id']], 60 * 60 * 24 * 30);

        return $this->response->auth($token);
    }

    /**
     * @return ResponseInterface
     * @api /admin/index/router
     */
    #[
        RequestMapping(path: 'router', methods: ['GET', 'POST']),
        Middlewares([
//            MiddlewareAdminAuthentication::class,
        ]),
        Scene,
    ]
    public function router(): ResponseInterface
    {
        /**
         * @var ModelAdminAdministrator $userinfo
         */
        $userinfo = Context::get('userinfo');

        $roleId = $userinfo->roleId ?? 0;

        $routerIdArr = $this->serviceAdminRoles->getRouterIds($roleId);

        $router = $this->serviceAdminRouter->getRouterList($routerIdArr);

        return $this->response->success($router);
    }
}
