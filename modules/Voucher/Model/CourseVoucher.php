<?php

namespace Modules\Voucher\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Course\Model\Course;

class CourseVoucher extends Model{
    use SoftDeletes;

    protected $table = "course_vouchers";

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
        if(isset($filter['course_id'])){
            $query->where('course_id', $filter['course_id']);
        }

        return $query;
    }

    /**
     * @return BelongsTo
     */
    public function course(){
        return $this->belongsTo(Course::class, 'course_id');
    }
}
