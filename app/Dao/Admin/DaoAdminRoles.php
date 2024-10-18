<?php
declare(strict_types=1);

namespace App\Dao\Admin;

use App\Abstract\AbstractDao;
use App\Model\Admin\ModelAdminRoles;

/**
 * @DaoAdminRoles
 * @\App\Dao\Admin\DaoAdminRoles
 */
class DaoAdminRoles extends AbstractDao
{

    public function __construct(ModelAdminRoles $model)
    {
        $this->model = $model;
    }
}