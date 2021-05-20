<?php

namespace Modules\Service\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Model\BaseModel;
use Modules\Base\Model\Status;

/**
 * Class Service
 * @package Modules\Service\Model
 */
class Service extends BaseModel{
    use SoftDeletes;

    protected $table = "services";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $guarded = [];

    public $timestamps = true;

    /**
     * @param $filter
     * @return Builder
     */
    public static function filter($filter){
        $query = self::with('type');
        $query = $query->whereHas('type', function($query){
            return $query->where('status', Status::STATUS_ACTIVE);
        });
        if(isset($filter['name'])){
            $query->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }

        return $query;
    }

    /**
     * @return BelongsTo
     */
    public function type(){
        return $this->belongsTo(ServiceType::class);
    }
}
