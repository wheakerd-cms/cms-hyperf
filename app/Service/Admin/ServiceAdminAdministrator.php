<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Dao\Admin\DaoAdministrator;
use Hyperf\Di\Annotation\Inject;

/**
 * @ServiceAdminAdministrator
 * @\App\Service\Admin\ServiceAdminAdministrator
 */
class ServiceAdminAdministrator
{

    /**
     * @var DaoAdministrator $daoAdministrator
     */
    #[Inject]
    protected DaoAdministrator $daoAdministrator;
}