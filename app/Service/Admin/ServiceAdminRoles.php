<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Dao\Admin\DaoAdminRoles;
use Hyperf\Di\Annotation\Inject;

/**
 * @ServiceAdminRoles
 * @\App\Service\Admin\ServiceAdminRoles
 */
class ServiceAdminRoles
{

    #[Inject]
    protected DaoAdminRoles $daoAdminRoles;

    public function getRouterIds(int $roleId): array
    {
        $list = $this->daoAdminRoles->whereSelect([
            ['role_id' => $roleId],
        ]);

        return array_column($list, 'router_id');
    }
}