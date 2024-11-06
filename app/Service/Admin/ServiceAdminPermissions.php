<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Dao\Admin\DaoAdminRoles;
use App\Dao\Admin\DaoAdminRouter;
use Hyperf\Di\Annotation\Inject;

/**
 * @ServiceAdminPermissions
 * @\App\Service\Admin\ServiceAdminPermissions
 */
class ServiceAdminPermissions
{
    #[Inject]
    protected DaoAdminRoles $daoAdminRoles;
    #[Inject]
    protected DaoAdminRouter $daoAdminRouter;

    private function getMenuList(array $list): array
    {
        return $list;
    }

    public function getRoutes(int $roleId): array
    {
        if ($roleId === 0) {
            $rotes = $this->daoAdminRouter->select();
        } else {
            $routerIdArr = $this->daoAdminRoles->find($roleId) ?? [];

            $rotes = $this->daoAdminRouter->select(
                where: ['id', 'in', $routerIdArr],
            );
        }

        return $this->getMenuList($rotes);
    }
}