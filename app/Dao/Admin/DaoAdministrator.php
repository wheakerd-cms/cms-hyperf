<?php
declare(strict_types=1);

namespace App\Dao\Admin;

use App\Abstract\AbstractDao;
use App\Model\Admin\ModelAdminAdministrator;
use App\Security\Admin\SecurityAdminJws;
use Hyperf\Di\Annotation\Inject;

/**
 * @DaoAdminAdministrator
 * @\App\Dao\DaoAdminAdministrator
 */
class DaoAdministrator extends AbstractDao
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