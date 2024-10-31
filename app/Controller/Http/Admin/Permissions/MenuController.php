<?php
declare(strict_types=1);

namespace App\Controller\Http\Admin\Permissions;

use App\Abstract\AbstractControllerHttp;
use App\Dao\Admin\DaoAdminRouter;
use App\Middleware\Admin\MiddlewareAdminAuthentication;
use App\Request\Admin\RequestAdminRouter;
use App\Utils\Helper\Functions;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Validation\Annotation\Scene;
use Psr\Http\Message\ResponseInterface;

/**
 * @MenuController
 * @\App\Controller\Http\Admin\Permissions\MenuController
 */
#[
    Controller(prefix: '/admin/permissions/menu'),
    Middlewares([
        MiddlewareAdminAuthentication::class,
    ]),
]
class MenuController extends AbstractControllerHttp
{

    /**
     * @var DaoAdminRouter $adminRouter
     */
    #[Inject]
    protected DaoAdminRouter $dao;

    /**
     * @param RequestAdminRouter $request
     * @return ResponseInterface
     * @api /admin/permission/menu/save
     */
    #[
        RequestMapping(path: 'save', methods: ['POST']),
        Scene,
    ]
    public function save(RequestAdminRouter $request): ResponseInterface
    {
        $data = $request->validated();

        if (array_key_exists('id', $data)) {
            $id = $data['id'];
            unset($data['id']);
            $saved = $this->dao->update($id, $data);
            goto end;
        }

        $saved = $this->dao->insert($data);

        end:
        return $saved ? $this->response->success() : $this->response->error();
    }

    /**
     * @param RequestAdminRouter $request
     * @return ResponseInterface
     * @api /admin/permission/menu/table
     */
    #[
        RequestMapping(path: 'table', methods: ['POST']),
        Scene,
    ]
    public function table(RequestAdminRouter $request): ResponseInterface
    {
        $params = $request->all();

        $paginator = $this->dao->paginator(
            ranks: [$params ['currentPage'], $params ['pageSize']],
            params: $params,
        );
        $collection = $paginator->getCollection()->toArray();

        $list = Functions::listToTree(
            $collection,
            pid: 'parentId',
        );
        $total = $paginator->total();

        return $this->response->success(
            compact('list', 'total')
        );
    }

    /**
     * @return ResponseInterface
     * @api /admin/permission/menu/table
     */
    #[
        RequestMapping(path: 'update', methods: ['POST']),
        Scene,
    ]
    public function update(): ResponseInterface
    {
        $all = $this->request->all();
        $id = $all['id'];
        unset($all['id']);
        $updated = $this->dao->update($id, $all);
        return $updated ? $this->response->success() : $this->response->error();
    }

    /**
     * @return ResponseInterface
     * @api /admin/permission/menu/select
     */
    #[
        RequestMapping(path: 'select', methods: ['GET']),
    ]
    public function select(): ResponseInterface
    {
        $data = $this->dao->select(['id', 'parent_id', 'name']);
        $list = Functions::listToTree(
            $data,
            pid: 'parentId',
        );

        return $this->response->success($list);
    }
}