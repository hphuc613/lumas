<?php

namespace Modules\Voucher\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Course\Model\Course;
use Modules\Service\Model\Service;

class Voucher extends Model {
    use SoftDeletes;

    protected $table = "vouchers";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $guarded = [];

    public $timestamps = true;

    const SERVICE_TYPE = "service";

    const COURSE_TYPE = "course";

    /**
     * @param $filter
     * @return Builder
     */
    public static function filter($filter) {
        $query = self::query();
        if (isset($filter['code'])) {
            $query->where('code', 'LIKE', '%' . $filter['code'] . '%');
        }
        if (isset($filter['type'])) {
            $query->where('type', $filter['type']);
        }

        return $query;
    }

    /**
     * @return string[]
     */
    public static function getTypeList() {
        return [
            self::SERVICE_TYPE => trans('Service'),
            self::COURSE_TYPE  => trans('Course')
        ];
    }

    /**
     * @return BelongsTo
     */
    public function service() {
        return $this->belongsTo(Service::class, 'parent_id');
    }

    /**
     * @return BelongsTo
     */
    public function course() {
        return $this->belongsTo(Course::class, 'parent_id');
    }
}
