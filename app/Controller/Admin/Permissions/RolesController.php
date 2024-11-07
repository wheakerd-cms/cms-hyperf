<?php
declare(strict_types=1);

namespace App\Controller\Admin\Permissions;

use App\Abstract\Controller\AbstractAdminController;
use App\Dao\Admin\DaoAdminRoles;
use App\Middleware\Admin\MiddlewareAdminAuthentication;
use App\Traits\Mapping\MappingAdminDelete;
use App\Traits\Mapping\MappingAdminList;
use App\Traits\Mapping\MappingAdminSave;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;

/**
 * @RolesController
 * @\App\Controller\Admin\Permissions\RolesController
 */
#[
    Controller(prefix: '/admin/permissions/roles'),
    Middlewares([
        MiddlewareAdminAuthentication::class,
    ]),
]
class RolesController extends AbstractAdminController
{

    #[Inject]
    protected DaoAdminRoles $dao;

    use MappingAdminDelete, MappingAdminList, MappingAdminSave;
}