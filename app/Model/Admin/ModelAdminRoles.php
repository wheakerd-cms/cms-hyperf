<?php
declare(strict_types=1);

namespace App\Model\Admin;

use App\Abstract\AbstractModel;
use Carbon\Carbon;

/**
 * @ModelAdminRoles
 * @\App\Model\Admin\ModelAdminRoles
 * @property int $id 主键
 * @property int $name 角色名称
 * @property array $router 路由权限
 * @property Carbon $createTime 创建时间
 * @property Carbon $updateTime 更新时间
 * @property Carbon|null $deleteTime 软删除，删除时间
 */
class ModelAdminRoles extends AbstractModel
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'admin_roles';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'id',
        'parent_id',
        'name',
        'router',
        'create_time',
        'update_time',
        'delete_time',
    ];

    /**
     * The attributes that should be cast to native types.
     * @formatter:off
     */
    protected array $casts = [
        'id'          => 'integer',
        'parent_id'   => 'integer',
        'name'        => 'string',
        'router'      => 'array',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'integer',
    ];
    //  @formatter:on

    public string $parentIdAttribute = 'parent_id';
}
