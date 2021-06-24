<?php

namespace Modules\User\Model;

use App\User as BaseUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;
use Modules\Appointment\Model\Appointment;
use Modules\Order\Model\Order;
use Modules\Role\Model\Role;
use Modules\Setting\Model\CommissionRateSetting;

/**
 * Class User
 *
 * @package Modules\User\Model
 */
class User extends BaseUser{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    const MANAGER = 'manager';

    /**
     * @param $filter
     * @return Builder
     */
    public static function filter($filter){
        $query = self::query();
        if(isset($filter['name'])){
            $query->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }
        if(isset($filter['role_id'])){
            $query->whereHas('roles', function($qr) use ($filter){
                $qr->where('role_id', $filter['role_id']);
            });
        }
        if(isset($filter['status'])){
            $query->where('status', $filter['status']);
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
     * @return int
     */
    public function getCommissionRate(){
        $rates = $this->getRoleAttribute()->commissionRates;
        $data  = 0;

        $target_by = $this->getTargetBy();

        if($target_by === CommissionRateSetting::PERSON_INCOME){
            $income = $this->orders()->whereMonth('updated_at', formatDate(time(), 'm'))->sum('total_price');
        }else{
            $income = Order::query()->whereMonth('updated_at', formatDate(time(), 'm'))->sum('total_price');
        }

        foreach($rates as $rate){
            if((int)$income >= (int)$rate->target){
                $data = (int)$rate->rate;
            }
        }

        return $data;
    }

    /**
     * @return HigherOrderBuilderProxy|mixed
     */
    public function getTargetBy(){
        $setting_data = CommissionRateSetting::query()
                                             ->orWhere(function($query){
                                                 $query->where('key', CommissionRateSetting::COMPANY_INCOME)
                                                       ->whereJsonContains('value', $this->getRoleAttribute()->id);
                                             })
                                             ->orWhere(function($query){
                                                 $query->where('key', CommissionRateSetting::PERSON_INCOME)
                                                       ->whereJsonContains('value', $this->getRoleAttribute()->id);
                                             })->first();

        return $setting_data->key;
    }

    /**
     *
     */
    public function getSalaryCurrentMonth(){
        return $this->salaries()->where('month', formatDate(time(), 'm/Y'))->first();
    }

    /**
     * @return HasMany
     */
    public function salaries(){
        return $this->hasMany(Salary::class, 'user_id');
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
     * @return HasMany
     */
    public function orders(){
        return $this->hasMany(Order::class, 'updated_by');
    }

    /**
     * @return Builder|Model|object|null
     */
    public function getRoleAttribute(){
        return Role::query()
                   ->with('commissionRates')
                   ->where('id', $this->roles->first()->role->id)
                   ->first();
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
