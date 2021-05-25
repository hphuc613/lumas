<?php

namespace Modules\Voucher\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Service\Model\Service;

class Voucher extends Model{
    use SoftDeletes;

    protected $table = "vouchers";

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
        if(isset($filter['code'])){
            $query->where('code', 'LIKE', '%' . $filter['code'] . '%');
        }

        return $query;
    }

    /**
     * @return BelongsTo
     */
    public function service(){
        return $this->belongsTo(Service::class, 'service_id');
    }
}
