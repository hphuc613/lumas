<?php

namespace Modules\User\Model;

use App\AppHelpers\Helper;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Modules\Base\Model\BaseModel;
use Modules\Role\Model\Role;

/**
 * Class UserRole
 * @package Modules\User\Model
 */
class UserRole extends BaseModel{

    protected $table = 'user_role';

    public $timestamps = false;

    public static function updateUserRole($user, $role_id){
        /** Update User Role */
        if(Helper::segment(2) === 'create'){
            $user_role          = new self();
            $user_role->user_id = $user->id;
            $user_role->role_id = $role_id ?? Auth::user()->getRoleAttribute()->id;
            $user_role->save();
        }elseif(Helper::segment(2) === 'update'){
            if($user->roles->isNotEmpty() && !empty($role_id)){
                self::where('user_id', $user->id)->update(['role_id' => $role_id]);
            }
            if($user->roles->isEmpty()){
                $user_role          = new self();
                $user_role->user_id = $user->id;
                $user_role->role_id = !empty($role_id) ? $role_id : Auth::user()->getRoleAttribute()->id;
                $user_role->save();
            }
        }
    }

    /**
     * @return BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function role(){
        return $this->belongsTo(Role::class);
    }
}
