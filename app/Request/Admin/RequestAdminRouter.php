<?php
declare(strict_types=1);

namespace App\Request\Admin;

use App\Dao\Admin\DaoAdminRouter;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Validation\Request\FormRequest;

/**
 * @RequestAdminRouter
 * @\App\Request\Admin\RequestAdminRouter
 */
class RequestAdminRouter extends FormRequest
{

    /**
     * @var DaoAdminRouter $dao
     */
    #[Inject]
    protected DaoAdminRouter $dao;

    /**
     * @var array|array[] $scenes
     */
    protected array $scenes = [
        'save' => [
            'id',
            'parentId',
            'type',
            'title',
            'name',
            'component',
            'path',
            'icon',
            'permissions',
            'status',
            'dev',
            'fixed',
            'sort',
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
            'parentId'    => 'nullable|integer:strict',
            'type'        => 'required|integer:strict',
            'title'       => 'required|string',
            'name'        => 'required|string',
            'component'   => 'required|string',
            'path'        => 'required|string',
            'icon'        => 'required|string',
            'permissions' => 'nullable|array',
            'status'      => 'required|boolean:strict',
            'dev'         => 'required|boolean:strict',
            'fixed'       => 'required|boolean:strict',
            'sort'        => 'nullable|integer:strict',
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