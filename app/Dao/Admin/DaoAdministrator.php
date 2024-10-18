<?php
declare(strict_types=1);

namespace App\Dao\Admin;

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

    public function findById(int $id): array
    {
        return $this->model->newQuery()
            ->find($id)->toArray();
    }

    public function findByUsername(string $username): ?ModelAdminAdministrator
    {
        return $this->model->newQuery()
            ->where('username', $username)
            ->get()?->first();
    }
}