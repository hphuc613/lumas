<?php

namespace Modules\Service\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Model\BaseModel;

class ServiceType extends BaseModel{
    use SoftDeletes;

    protected $table = "service_types";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $guarded = [];

    public $timestamps = true;

    public static function filter($filter){
        $query = self::query();
        if(isset($filter['name'])){
            $query->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }

        return $query;
    }

    public function services(){
        return $this->hasMany(Service::class, 'type_id');
    }
}
