<?php

namespace Modules\Member\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Modules\Base\Model\BaseModel;
use Modules\Course\Model\Course;
use Modules\Voucher\Model\CourseVoucher;

class MemberCourse extends BaseModel{
    use SoftDeletes;

    protected $table = "member_courses";

    protected $dates = ["deleted_at"];

    protected $guarded = [];

    public $timestamps = true;

    const COMPLETED_STATUS = 1;

    const PROGRESSING_STATUS = 0;


    /**
     * @param $filter
     * @param $member_id
     * @return Builder
     */
    public static function filter($filter, $member_id){
        $query = self::query();
        if(isset($filter['code'])){
            $query->where('code', 'LIKE', '%' . $filter['code'] . '%');
        }
        if(isset($filter['course_search'])){
            $query->where('course_id', $filter['course_search']);
        }
        $query->where('member_id', $member_id)
              ->whereRaw('deduct_quantity < quantity')
              ->orderBy("created_at", "DESC");

        return $query;
    }

    /**
     * @param $filter
     * @param $member_id
     * @return Builder
     */
    public static function filterCompleted($filter, $member_id){
        $query = self::query();
        if(isset($filter['code_completed'])){
            $query->where('code', $filter['code_completed']);
        }
        if(isset($filter['service_search_completed'])){
            $query->where('service_id', $filter['service_search_completed']);
        }
        $query->where('member_id', $member_id)
              ->whereRaw('deduct_quantity = quantity')
              ->orderBy("created_at", "DESC");

        return $query;
    }

    /**
     * @return string[]
     */
    public static function getStatus(){
        return [
            self::COMPLETED_STATUS   => 'Completed',
            self::PROGRESSING_STATUS => 'Progressing'
        ];
    }

    /**
     * @param $member_id
     * @param false $search_history
     * @return array
     */
    public static function getArrayByMember($member_id, $search_history = false){
        $query = self::query()->where('member_id', $member_id);
        if($search_history){
            $query->whereRaw('deduct_quantity = quantity');
        }else{
            $query->whereRaw('deduct_quantity < quantity');
        }
        $query = $query->get();
        $data  = [];
        foreach($query as $value){
            $data[$value->course_id] = $value->course->name;
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public function getRemaining(){
        return $this->quantity - $this->deduct_quantity;
    }

    /**
     * @return string
     */
    public function generateCode(){
        return 'LMC' . $this->member->id . 'C' . $this->course->id . 'T' . time();
    }

    /**
     * @param $data
     * @param $appointment
     */
    public function eSign($data, $appointment){
        if(empty($data['signature'])){
            $data['signature'] = Auth::user()->name;
        }

        $data['start']            = $this->updated_at;
        $data['end']              = formatDate(time(), 'Y-m-d H:i:s');
        $data['member_course_id'] = $this->id;
        $data['appointment_id']   = $appointment->id;
        $data['updated_by']       = Auth::id();
        $history                  = new MemberCourseHistory($data);
        $history->save();
    }

    /**
     * @param $data
     * @return bool
     */
    public static function insertData($data){
        $member_course = self::query()
                             ->where('member_id', $data['member_id'])
                             ->where('course_id', $data['course_id'])
                             ->where('voucher_id', $data['voucher_id'])
                             ->where('price', $data['price'])
                             ->whereRaw('deduct_quantity < quantity')
                             ->first();

        if(empty($member_course)){
            $member_course        = new MemberCourse($data);
            $member_course->code  = $member_course->generateCode();
            $member_course->price =
                !empty($member_course->voucher_id) ? $member_course->voucher->price : $member_course->course->price;
            $member_course->save();
        }else{

            /** Check when no Voucher */
            if(empty($member_course->voucher_id)){
                $check_same_price = (int)$member_course->price === (int)$member_course->course->price;
            }else{
                $voucher          = $member_course->voucher;
                $check_same_price = (int)$voucher->price === (int)$member_course->price;
            }

            if($check_same_price){
                $data['quantity'] = (int)$member_course->quantity + (int)$data['quantity'];
                $member_course->update($data);
            }else{
                $member_course        = new MemberService($data);
                $member_course->code  = $member_course->generateCode();
                $member_course->price =
                    !empty($member_course->voucher_id) ? $member_course->voucher->price : $member_course->course->price;
                $member_course->save();
            }
        }

        return TRUE;
    }

    /**
     * @return BelongsTo
     */
    public function member(){
        return $this->belongsTo(Member::class, "member_id");
    }

    /**
     * @return BelongsTo
     */
    public function course(){
        return $this->belongsTo(Course::class, "course_id");
    }

    /**
     * @return BelongsTo
     */
    public function voucher(){
        return $this->belongsTo(CourseVoucher::class, "voucher_id");
    }

}
