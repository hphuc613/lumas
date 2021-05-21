<?php

namespace Modules\Voucher\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model{
    use SoftDeletes;

    protected $table = "vouchers";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $guarded = [];

    public $timestamps = true;

    /**
     * @param $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function filter($filter){
        $query = self::query();
        if(isset($filter['code'])){
            $query->where('code', 'LIKE', '%' . $filter['code'] . '%');
        }

        return $query;
    }

}
