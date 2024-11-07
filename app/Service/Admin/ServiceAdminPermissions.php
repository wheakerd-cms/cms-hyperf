<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Dao\Admin\DaoAdminAdministrator;
use App\Dao\Admin\DaoAdminRoles;
use App\Dao\Admin\DaoAdminRouter;
use App\Security\SecurityAdminJws;
use Hyperf\Di\Annotation\Inject;

/**
 * @ServiceAdminPermissions
 * @\App\Service\Admin\ServiceAdminPermissions
 */
class ServiceAdminPermissions
{

    #[Inject]
    protected DaoAdminAdministrator $dao;
    #[Inject]
    protected SecurityAdminJws $securityAdminJws;
    #[Inject]
    protected DaoAdminRoles $daoAdminRoles;
    #[Inject]
    protected DaoAdminRouter $daoAdminRouter;

    public function login(string $username, string $password): false|string
    {
        $data = $this->dao->getModel()->newQuery()
            ->where([
                'username' => $username,
            ])->first()->toArray();

        if (
            !$data
            || !password_verify($password, $data ['password'])
            || $data ['status'] === false
        ) {
            return false;
        }

        return $this->securityAdminJws->create(
            [
                'id' => $data ['id']
            ], 60 * 60 * 24 * 30
        );
    }

    /**
     * @formatter:off
     * @param array $list
     * @param int $parentId
     * @return array
     */
    private function getMenuList(array &$list, int $parentId = 0): array
    {
        $data = [];

        foreach ($list as &$item) if ($item['parentId'] == $parentId) {
            $metadata = [
                'path'      => $item['path'] ?? '',
                'name'      => $item['name'] ?? '',
                'component' => $item['component'] ?? '',
                'meta'      => $item,
            ];
            if ($children = $this->getMenuList($list, $item['id'])) {
                $metadata += compact('children');
            }
            $data [] = $metadata;
            unset($item);
        }

        return $data;
    }
    // @formatter:on

    public function getRoutes(int $roleId): array
    {
        $routers = $this->daoAdminRouter->select();

        if ($roleId !== 0) {
            $roleRouter = $this->daoAdminRoles->find($roleId);
            $routerIdArr = is_null($roleRouter) ? [] : $roleRouter ['router'];
            $routers = array_filter($routers, fn($item) => in_array($item ['id'], $routerIdArr));
        }

        return $this->getMenuList($routers);
    }
}