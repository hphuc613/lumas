<?php

namespace Modules\Member\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Appointment\Model\Appointment;
use Modules\Base\Model\BaseModel;
use Modules\User\Model\User;

class MemberCourseHistory extends BaseModel{
    use SoftDeletes;

    protected $table = "member_course_histories";

    protected $dates = ["deleted_at"];

    protected $guarded = [];

    public $timestamps = true;

    /**
     * @param $filter
     * @param $member_id
     * @param null $service_id
     * @return Builder
     */
    public static function filter($filter, $member_id, $service_id = null){
        $query = self::query()->with('memberCourse');
        $query->whereHas('memberCourse', function($ms_query) use ($member_id, $filter, $service_id){
            if(isset($filter['code_history'])){
                $ms_query->where('code', $filter['code_history']);
            }
            if(isset($filter['code'])){
                $ms_query->where('code', $filter['code']);
            }
            if(!empty($service_id)){
                $ms_query->where('course_id', $service_id);
            }
            $ms_query->where('member_id', $member_id);
        });
        $query->orderBy('created_at', 'DESC');

        return $query;
    }

    /**
     * @return BelongsTo
     */
    public function memberCourse(){
        return $this->belongsTo(MemberCourse::class, "member_course_id");
    }

    /**
     * @return BelongsTo
     */
    public function appointment(){
        return $this->belongsTo(Appointment::class, "appointment_id");
    }

    /**
     * @return BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class, "updated_by");
    }
}
