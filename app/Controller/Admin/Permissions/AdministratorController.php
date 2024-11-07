<?php
declare(strict_types=1);

namespace App\Controller\Admin\Permissions;

use App\Abstract\Controller\AbstractAdminController;
use App\Dao\Admin\DaoAdminAdministrator;
use App\Middleware\Admin\MiddlewareAdminAuthentication;
use App\Traits\Mapping\MappingAdminDelete;
use App\Traits\Mapping\MappingAdminList;
use App\Traits\Mapping\MappingAdminSave;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Validation\Annotation\Scene;
use Psr\Http\Message\ResponseInterface;

/**
 * @RolesController
 * @\App\Controller\Admin\Permissions\AdministratorController
 */
#[
    Controller(prefix: '/admin/permissions/administrator'),
    Middlewares([
        MiddlewareAdminAuthentication::class,
    ]),
]
class AdministratorController extends AbstractAdminController
{

    #[Inject]
    protected DaoAdminAdministrator $dao;

    use MappingAdminDelete, MappingAdminList, MappingAdminSave;

    /**
     * @return ResponseInterface
     * @api /admin/permissions/roles/select
     */
    #[
        RequestMapping(path: 'select', methods: ['GET']),
        Scene,
    ]
    public function select(): ResponseInterface
    {
        return $this->response->success(
            data: $this->dao->select([], ['id', 'name'])
        );
    }
}