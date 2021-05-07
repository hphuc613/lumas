<?php

namespace Modules\Permission\Model;

use Modules\Base\Model\BaseModel;
use Modules\Role\Model\Role;

class PermissionRole extends BaseModel{

    protected $table = 'permission_role';

    public $timestamps = FALSE;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permission(){
        return $this->belongsTo(Permission::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(){
        return $this->belongsTo(Role::class);
    }

    /**
     * @param $permission_id
     * @param $role_id
     *
     * @return bool
     */
    public static function checkRolePermission($permission_id, $role_id){
        $data = self::where('permission_id', $permission_id)->where('role_id', $role_id)->first();
        return (!empty($data)) ? TRUE : FALSE;
    }
}
