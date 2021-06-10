<?php

namespace Modules\User\Model;

use App\User as BaseUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;
use Modules\Appointment\Model\Appointment;
use Modules\Base\Model\Status;
use Modules\Role\Model\Role;

/**
 * Class User
 *
 * @package Modules\User\Model
 */
class User extends BaseUser{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * @param $filter
     * @return Builder
     */
    public static function filter($filter){
        $query = self::query();
        if(isset($filter['name'])){
            $query->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }

        return $query;
    }

    /**
     * @param array $options
     * @return bool|void
     */
    public function save(array $options = []){
        $insert = Request::all();
        $this->beforeSave($this->attributes, $insert);
        parent::save($options);
        $this->afterSave($insert);
    }

    /**
     * @return Builder
     */
    public static function getStaff(){
        $staff = User::query()->whereHas('roles', function($qr){
            $qr->whereHas('role', function($r){
                $r->where('name', 'Staff');
            });
        })->where('status', Status::STATUS_ACTIVE);

        return $staff;
    }


    /**
     *
     */
    public function beforeSave($old_attributes, $insert){
    }

    /**
     * @param $insert
     */
    public function afterSave($insert){
        /** Update user role */
        UserRole::updateUserRole($this, $insert['role_id'] ?? null);
    }

    /**
     * @return HasMany
     */
    public function roles(){
        return $this->hasMany(UserRole::class);
    }

    /**
     * @return HasMany
     */
    public function appointments(){
        return $this->hasMany(Appointment::class, 'user_id');
    }

    /**
     * @return null
     */
    public function getRoleAttribute(){
        return $this->roles->first()->role ?? null;
    }

    /**
     * @return bool
     */
    public function isAdmin(){
        if($this->getRoleAttribute()->id == Role::getAdminRole()->id){
            return true;
        }
        return false;
    }
}
