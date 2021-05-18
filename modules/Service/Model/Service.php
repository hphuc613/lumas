<?php

namespace Modules\Service\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Model\BaseModel;

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
     * @return BelongsTo
     */
    public function type(){
        return $this->belongsTo(ServiceType::class);
    }
}
