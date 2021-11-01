<?php

namespace Modules\Role\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Model\BaseModel;
use Modules\User\Model\UserRole;

class Role extends BaseModel{

    use SoftDeletes;

    protected $table = 'roles';

    protected $primaryKey = 'id';

    protected $dates = ['deleted_at'];

    protected $guarded = [];

    public $timestamps = TRUE;

    const ADMINISTRATOR = 'Administrator';
    const THERAPIST     = 'Therapist';
    const MANAGER       = 'Manager';
    const FRONT_DESK    = 'Front Desk';

    /**
     * @param $filter
     * @return Builder
     */
    public static function filter($filter){
        $query = self::query();
        if (isset($filter['name'])) {
            $query->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }

        return $query;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getName($id){
        $data = self::find($id);

        return $data->name;
    }

    /**
     * @return HasMany
     */
    public function users(){
        return $this->hasMany(UserRole::class);
    }

    /**
     * @return bool
     */
    public function checkUserHasRole(){
        if ($this->users->isEmpty()) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * @return mixed
     */
    public static function getAdminRole(){
        return self::where('name', self::ADMINISTRATOR)->first();
    }

    /**
     * @return mixed
     */
    public function commissionRates(){
        return $this->hasMany(CommissionRate::class, 'role_id');
    }

    /**
     * @return array
     */
    public static function getDefaultRoles(){
        return [self::ADMINISTRATOR, self::THERAPIST, self::MANAGER, self::FRONT_DESK];
    }
}
