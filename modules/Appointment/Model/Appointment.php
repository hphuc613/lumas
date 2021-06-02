<?php

namespace Modules\Appointment\Model;

use App\AppHelpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Course\Model\Course;
use Modules\Member\Model\Member;
use Modules\Member\Model\MemberServiceHistory;
use Modules\Service\Model\Service;
use Modules\Store\Model\Store;
use Modules\User\Model\User;

class Appointment extends Model{
    use SoftDeletes;

    protected $table = "appointments";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $guarded = [];

    public $timestamps = true;

    const SERVICE_TYPE = "service";

    const COURSE_TYPE = "course";

    const ABORT_STATUS       = -1;
    const WAITING_STATUS     = 0;
    const PROGRESSING_STATUS = 1;
    const COMPLETED_STATUS   = 2;

    /**
     * @return string[]
     */
    public static function getTypeList(){
        return [
            self::SERVICE_TYPE => trans('Service'),
            self::COURSE_TYPE  => trans('Course')
        ];
    }

    /**
     * @return array
     */
    public static function getStatuses(): array{
        return [
            self::WAITING_STATUS     => trans('Waiting'),
            self::PROGRESSING_STATUS => trans('Progressing'),
            self::COMPLETED_STATUS   => trans('Completed'),
            self::ABORT_STATUS       => trans('Abort')
        ];
    }

    /**
     * @return array
     */
    public function getServiceList(){
        $data = Helper::isJson($this->service_ids, 1);
        $list = [];
        if($data){
            foreach($data as $id){
                $list[] = Service::find($id);
            }
        }

        return $list;
    }

    /**
     * @return array
     */
    public function getCourseList(){
        $data = Helper::isJson($this->course_ids, 1);
        $list = [];
        if($data){
            foreach($data as $id){
                $list[] = Course::find($id);
            }
        }

        return $list;
    }

    /**
     * @return string
     */
    public function getColorStatus(){
        $color = '#fec107';
        if($this->status === self::WAITING_STATUS){
            $color = '#03a9f3';
            if(strtotime($this->time) < time()){
                $color = '#e46a76';
            }
        }elseif($this->status === self::COMPLETED_STATUS){
            $color = '#00c292';
        }elseif($this->status === self::ABORT_STATUS){
            $color = '#aaa';
        }

        return $color;
    }

    /**
     * @return BelongsTo
     */
    public function store(){
        return $this->belongsTo(Store::class, 'store_id');
    }

    /**
     * @return BelongsTo
     */
    public function member(){
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function memberServiceHistory(){
        return $this->hasMany(MemberServiceHistory::class, 'appointment_id');
    }

    /**
     * @return BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
