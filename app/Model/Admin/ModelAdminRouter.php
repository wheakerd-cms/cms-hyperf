<?php
declare(strict_types=1);

namespace App\Model\Admin;

use App\Abstract\AbstractModel;
use Carbon\Carbon;

/**
 * @property int $id 主键
 * @property int $parentId 父级 ID
 * @property int $type 菜单类型
 * @property string $menuName 菜单名称
 * @property string $componentName 组件名称
 * @property string $component 组件路径
 * @property string $icon 图标
 * @property string $permissions 操作权限
 * @property int $status 状态
 * @property int $dev 是否用于开发环境
 * @property int $fixed 是否在导航固定该标签
 * @property int $sort 排序
 * @property Carbon $createTime 创建时间
 * @property Carbon $updateTime 更新时间
 * @property Carbon|null $deleteTime 软删除，删除时间
 */
class ModelAdminRouter extends AbstractModel
{

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'admin_router';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'id',
        'parent_id',
        'type',
        'name',
        'component_name',
        'component',
        'path',
        'icon',
        'permissions',
        'status',
        'dev',
        'fixed',
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
        'type'        => 'integer',
        'menu_name'   => 'string',
        'name'        => 'string',
        'component'   => 'string',
        'path'        => 'string',
        'icon'        => 'string',
        'permissions' => 'string',
        'status'      => 'boolean',
        'dev'         => 'boolean',
        'fixed'       => 'boolean',
        'sort'        => 'integer',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'datetime',
    ];
    // @formatter:on

    /**
     * @param array $value
     * @return void
     * @noinspection PhpUnused
     */
    public function setPermissionsAttribute(array $value): void
    {
        $this->attributes ['permissions'] = implode(',', $value);
    }

    /**
     * @param string $value
     * @return array
     * @noinspection PhpUnused
     */
    public function getPermissionsAttribute(string $value): array
    {
        return explode(',', $value);
    }
}
