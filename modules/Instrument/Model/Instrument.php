<?php

namespace Modules\Instrument\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Appointment\Model\Appointment;
use Modules\Base\Model\BaseModel;

class Instrument extends BaseModel{
    use SoftDeletes;

    protected $table = "instruments";

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
        if (isset($filter['name'])) {
            $query->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }

        return $query;
    }

    /**
     * @return HasMany
     */
    public function appointments(){
        return $this->hasMany(Appointment::class);
    }
}
