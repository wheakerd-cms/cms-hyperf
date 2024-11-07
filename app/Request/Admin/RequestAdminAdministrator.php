<?php
declare(strict_types=1);

namespace App\Request\Admin;

use App\Dao\Admin\DaoAdminAdministrator;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Validation\Request\FormRequest;

/**
 * @RequestAdminAdministrator
 * @\App\Request\Admin\RequestAdminAdministrator
 */
class RequestAdminAdministrator extends FormRequest
{

    /**
     * @var DaoAdminAdministrator $dao
     */
    #[Inject]
    protected DaoAdminAdministrator $dao;

    /**
     * @var array|array[] $scenes
     */
    protected array $scenes = [
        'login' => [
            'username',
            'password',
        ],
        'save' => [
            'username',
            'password',
            'role_id',
            'status',
        ],
        'delete' => ['ids'],
        'table' => [
            'currentPage',
            'pageSize',
        ],
    ];

    /**
     * @return array[]
     * @formatter:off
     */
    public function rules(): array
    {
        return [
            'id'          => 'required|integer:strict',
            'username'    => 'required|string',
            'password'    => 'required|string',
            'role_id'     => 'required|integer',
            'status'      => 'required|integer',
            'currentPage' => 'required|integer:strict|min:1',
            'pageSizes'   => 'required|integer:strict|between:10,100',
            'ids'         => 'required|array',
        ];
    }
    // @formatter:on

    /**
     * @return bool
     * @noinspection PhpUnused
     */
    protected function authorize(): bool
    {
        return true;
    }
}