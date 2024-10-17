<?php /** @formatter:off */
declare(strict_types=1);

namespace App\Request\Admin;

use app\Dao\Admin\DaoAdministrator;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Validation\Request\FormRequest;

/**
 * @RequestAdminAdministrator
 * @\App\Request\Admin\RequestAdminAdministrator
 */
class RequestAdminAdministrator extends FormRequest
{

    /**
     * @var DaoAdministrator $dao
     */
    #[Inject]
    protected DaoAdministrator $dao;

    protected array $scenes = [];

    public function rules(): array
    {
        return [
            'id'          => ['required', 'integer:strict',],
            'username'    => ['required', 'string',],
            'password'    => ['required', 'string',],
            'role_id'     => ['required', 'integer',],
            'status'      => ['required', 'integer',],
            'currentPage' => ['required', 'integer:strict', 'min:1',],
            'pageSizes'   => ['required', 'integer:strict', 'between:10,100',],
        ];
    }

    protected function authorize(): bool
    {
        return true;
    }
}