<?php
declare(strict_types=1);

namespace App\Dao;

use App\Abstract\AbstractDao;
use app\Model\Admin\ModelAdminAdministrator;

/**
 * @DaoAdminAdministrator
 * @\App\Dao\DaoAdminAdministrator
 */
class DaoAdminAdministrator extends AbstractDao
{

    public function __construct(ModelAdminAdministrator $model)
    {
        $this->model = $model;
    }
}