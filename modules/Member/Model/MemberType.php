<?php

namespace Modules\Member\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Model\BaseModel;

class MemberType extends BaseModel{
    use SoftDeletes;

    protected $table = "member_types";

    protected $dates = ["deleted_at"];

    protected $guarded = [];

    public $timestamps = true;

    /**
     * @param $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function filter($filter){
        $query = self::query();
        if(isset($filter['name'])){
            $query->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }

        return $query;
    }

    /**
     * @return HasMany
     */
    public function members(){
        return $this->hasMany(Member::class, 'type_id');
    }
}
