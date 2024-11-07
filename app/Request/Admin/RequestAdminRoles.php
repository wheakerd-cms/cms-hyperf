<?php
declare(strict_types=1);

namespace App\Request\Admin;

use App\Dao\Admin\DaoAdminRoles;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Validation\Request\FormRequest;

/**
 * @RequestAdminRoles
 * @\App\Request\Admin\RequestAdminRoles
 */
class RequestAdminRoles extends FormRequest
{

    /**
     * @var DaoAdminRoles $dao
     */
    #[Inject]
    protected DaoAdminRoles $dao;

    /**
     * @var array|array[] $scenes
     */
    protected array $scenes = [
        'save' => [
            'id',
            'name',
            'router',
        ],
        'delete' => ['ids'],
        'table' => [
            'currentPage',
            'pageSize',
        ],
    ];

    /**
     * @return string[]
     * @formatter:off
     */
    public function rules(): array
    {
        return [
            'id'          => 'nullable|integer:strict',
            'parent_id'   => 'required|integer:strict',
            'name'        => 'required|string',
            'router'      => 'nullable|array',
            'currentPage' => 'required|integer:strict|min:1',
            'pageSize'    => 'required|integer:strict|between:10,100',
            'ids'         => 'required|array',
        ];
    }
    //  @formatter:on

    /**
     * @return bool
     * @noinspection PhpUnused
     */
    protected function authorize(): bool
    {
        return true;
    }
}