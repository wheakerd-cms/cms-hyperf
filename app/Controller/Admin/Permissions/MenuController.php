<?php
declare(strict_types=1);

namespace App\Controller\Admin\Permissions;

use App\Abstract\Controller\AbstractAdminController;
use App\Dao\Admin\DaoAdminRouter;
use App\Middleware\Admin\MiddlewareAdminAuthentication;
use App\Traits\Mapping\MappingAdminDelete;
use App\Traits\Mapping\MappingAdminList;
use App\Traits\Mapping\MappingAdminSave;
use App\Utils\HelperFunctions;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * @MenuController
 * @\App\Controller\Admin\Permissions\MenuController
 */
#[
    Controller(prefix: '/admin/permissions/menu'),
    Middlewares([
        MiddlewareAdminAuthentication::class,
    ]),
]
class MenuController extends AbstractAdminController
{

    #[Inject]
    protected DaoAdminRouter $dao;

    use MappingAdminDelete, MappingAdminList, MappingAdminSave;

    /**
     * @return ResponseInterface
     * @api /admin/permissions/menu/select
     */
    #[
        RequestMapping(path: 'select', methods: ['GET']),
    ]
    public function select(): ResponseInterface
    {
        $data = $this->dao->getModel()->newQuery()
            ->select(['id', 'parent_id', 'name'])
            ->get()->toArray();
        $list = HelperFunctions::listToTree(
            $data,
            pid: 'parentId',
        );

        return $this->response->success($list);
    }

    /**
     * @return ResponseInterface
     * @api /admin/permissions/menu/select-menu
     */
    #[
        RequestMapping(path: 'select-menu', methods: ['GET']),
    ]
    public function selectMenu(): ResponseInterface
    {
        $data = $this->dao->getModel()->newQuery()
            ->select(['id', 'parent_id', 'title'])
            ->get()->toArray();

        $list = HelperFunctions::listToTree(
            $data,
            pid: 'parentId',
        );

        return $this->response->success($list);
    }
}