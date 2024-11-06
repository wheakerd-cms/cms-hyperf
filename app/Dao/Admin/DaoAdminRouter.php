<?php
declare (strict_types=1);

namespace App\Dao\Admin;

use App\Abstract\AbstractDao;
use App\Model\Admin\ModelAdminRouter;

/**
 * @DaoAdminRouter
 * @\App\Dao\Admin\DaoAdminRouter
 */
class DaoAdminRouter extends AbstractDao
{

    public function __construct(ModelAdminRouter $model)
    {
        parent::__construct($model);
    }
}