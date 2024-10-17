<?php
declare(strict_types=1);

namespace App\Controller\Admin\Permission;

use App\Abstract\AbstractControllerHttp;
use App\Dao\Admin\DaoAdminRouter;
use App\Request\Admin\RequestAdminRouter;
use App\Utils\Helper\Functions;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Validation\Annotation\Scene;
use Psr\Http\Message\ResponseInterface;

/**
 * @MenuController
 * @\App\Controller\Admin\Permission\MenuController
 */
#[
    Controller(prefix: '/admin/permission/menu'),
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
            $saved = $this->dao->update($data ['id'], $data);
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
     * @api /admin/permission/menu/select
     */
    #[
        RequestMapping(path: 'select', methods: ['GET']),
    ]
    public function select(): ResponseInterface
    {
        return $this->response->success(
            $this->dao->select()
        );
    }
}