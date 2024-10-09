<?php /** @formatter:off */
declare(strict_types=1);

namespace app\Model\Admin;

use App\Abstract\AbstractModel;
use Carbon\Carbon;

/**
 * @ModelAdminAdministrator
 * @\app\Model\Admin\ModelAdminAdministrator
 * @property int $id 主键
 * @property string $username 用户名
 * @property string $password 密码 (此字段数据较为特殊,请查阅加密算法文档)
 * @property int $roleId 角色组ID
 * @property int $status 状态
 * @property Carbon $createTime 创建时间
 * @property Carbon $updateTime 更新时间
 * @property Carbon|null $deleteTime 软删除，删除时间
 */
class ModelAdminAdministrator extends AbstractModel
{

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'admin_administrator';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'id',
        'username',
        'password',
        'role_id',
        'status',
        'create_time',
        'update_time',
        'delete_time',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'id'          => 'integer',
        'username'    => 'string',
        'password'    => 'string',
        'role_id'     => 'integer',
        'status'      => 'integer',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'datetime',
    ];

    public array $scenes = [
        'login' => ['username', 'password'],
    ];

    public function rules(): array
    {
        return [
            'id'          => ['required', 'integer:strict',],
            'username'    => ['required', 'integer:strict',],
            'password'    => ['required', 'string',],
            'role_id'     => ['required', 'integer',],
            'status'      => ['required', 'integer',],
            'currentPage' => ['required', 'integer:strict', 'min:1',],
            'pageSizes'   => ['required', 'integer:strict', 'between:10,100',],
        ];
    }
}
