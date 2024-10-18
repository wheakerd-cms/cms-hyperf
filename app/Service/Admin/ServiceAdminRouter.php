<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Dao\Admin\DaoAdministrator;
use Hyperf\Di\Annotation\Inject;

/**
 * @ServiceAdminRouter
 * @\App\Service\Admin\ServiceAdminRouter
 */
class ServiceAdminRouter
{

    #[Inject]
    protected DaoAdministrator $daoAdministrator;

    public function getRouterList(array $routerIdArr = []): array
    {
        $list = empty($routerIdArr)
            ? $this->daoAdministrator->select()
            : $this->daoAdministrator->whereSelect([
                ['id', 'in', $routerIdArr],
            ]);

        return $list;
    }
}