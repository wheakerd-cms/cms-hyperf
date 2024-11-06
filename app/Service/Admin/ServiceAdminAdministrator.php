<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Dao\Admin\DaoAdministrator;
use App\Security\Admin\SecurityAdminJws;
use Hyperf\Di\Annotation\Inject;

/**
 * @ServiceAdminAdministrator
 * @\App\Service\Admin\ServiceAdminAdministrator
 */
class ServiceAdminAdministrator
{
    #[Inject]
    protected DaoAdministrator $dao;
    #[Inject]
    protected SecurityAdminJws $securityAdminJws;

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
}