<?php

namespace Modules\Store\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Model\BaseModel;

class Store extends BaseModel{
    use SoftDeletes;

    protected $table = "stores";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $guarded = [];

    public $timestamps = true;

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
     * @param null $status
     * @return array
     */
    public static function getArray($status = null){
        $query = self::select('id', 'name', 'address');
        if(!empty($status)){
            $query = $query->where('status', $status);
        }
        $query = $query->orderBy('name', 'asc')->get();

        $data = [];

        foreach($query as $item){
            $data[$item->id] = $item->name . ' | ' . $item->address;
        }

        return $data;
    }

}
