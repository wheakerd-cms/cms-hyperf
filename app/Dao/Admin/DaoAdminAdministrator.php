<?php
declare(strict_types=1);

namespace App\Dao\Admin;

use App\Abstract\AbstractDao;
use App\Model\Admin\ModelAdminAdministrator;

/**
 * @DaoAdminAdministrator
 * @\App\Dao\DaoAdminAdministrator
 */
class DaoAdminAdministrator extends AbstractDao
{

    public function __construct(ModelAdminAdministrator $model)
    {
        parent::__construct($model);
    }

    public function findById(int $id): array
    {
        return $this->getModel()->newQuery()
            ->find($id)->toArray();
    }
}