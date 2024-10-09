<?php
declare(strict_types=1);

namespace App\Request\Admin;

use App\Dao\DaoAdminAdministrator;
use App\Traits\Request\TraitRequestModel;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Validation\Request\FormRequest;

/**
 * @RequestAdminAdministrator
 * @\App\Request\Admin\RequestAdminAdministrator
 */
class RequestAdminAdministrator extends FormRequest
{

    use TraitRequestModel;

    /**
     * @var DaoAdminAdministrator $dao
     */
    #[Inject]
    protected DaoAdminAdministrator $dao;

    protected array $scenes = [];

    public function rules(): array
    {
        return $this->dao->getModel()->rules();
    }

    protected function authorize(): bool
    {
        return true;
    }
}