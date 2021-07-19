<?php

namespace Modules\Course\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Model\BaseModel;
use Modules\Member\Model\MemberCourse;
use Modules\Voucher\Model\CourseVoucher;

class Course extends BaseModel{
    use SoftDeletes;

    protected $table = "courses";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $guarded = [];

    public $timestamps = true;

    /**
     * @param $filter
     * @return Builder
     */
    public static function filter($filter){
        $query = self::query()->with('category');
        if(isset($filter['name'])){
            $query->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }
        if(isset($filter['category_id'])){
            $query->where('category_id', $filter['category_id']);
        }

        return $query;
    }


    /**
     * @param null $status
     * @param false $in
     * @param false $not_in
     * @return array
     */
    public static function getArray($status = null, $in = false, $not_in = false){
        $query = self::query()->select('id', 'name', 'price');
        if(!empty($status)){
            $query = $query->where('status', $status);
        }
        $query = $query->orderBy('name')->get();

        $data = [];

        foreach($query as $item){
            $data[$item->id] = $item->name . ' | ' . $item->price;
        }

        return $data;
    }

    /**
     * @return BelongsTo
     */
    public function category(){
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }

    /**
     * @return HasMany
     */
    public function vouchers(){
        return $this->hasMany(CourseVoucher::class, 'course_id');
    }

    /**
     * @return HasMany
     */
    public function memberCourses(){
        return $this->hasMany(MemberCourse::class);
    }
}
