<?php
declare(strict_types=1);

namespace app\Dao\Admin;

use App\Abstract\AbstractDao;
use App\Model\Admin\ModelAdminAdministrator;

/**
 * @DaoAdminAdministrator
 * @\App\Dao\DaoAdminAdministrator
 */
class DaoAdministrator extends AbstractDao
{

    public function __construct(ModelAdminAdministrator $model)
    {
        $this->model = $model;
    }
}