<?php

namespace Modules\Member\Model;

use App\Member as BaseMember;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Modules\Appointment\Model\Appointment;
use Modules\Base\Model\Status;
use Modules\Order\Model\Order;

class Member extends BaseMember{
    use SoftDeletes;

    protected $dates = ["deleted_at"];

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
        if(isset($filter['phone'])){
            $query->where('phone', 'LIKE', '%' . $filter['phone'] . '%');
        }
        if(isset($filter['email'])){
            $query->where('email', 'LIKE', '%' . $filter['email'] . '%');
        }
        if(isset($filter['status'])){
            $query->where('status', $filter['status']);
        }
        if(isset($filter['type_id'])){
            $query->where('type_id', $filter['type_id']);
        }

        return $query;
    }

    /**
     * @return string
     */
    public function getAvatar(){
        $avatar = $this->avatar;
        if(!empty($avatar) && File::exists(base_path() . '/storage/app/public/' . $avatar)){
            return url('/storage/' . $avatar);
        }
        return asset('/image/user.png');
    }

    /**
     * @param null $status
     * @return array
     */
    public static function getArray($status = null){
        $query = self::select('id', 'name', 'phone', 'email');
        if(!empty($status)){
            $query = $query->where('status', $status);
        }
        $query = $query->orderBy('name', 'asc')->get();

        $data = [];

        foreach($query as $item){
            $data[$item->id] = $item->name . ' | ' . $item->phone . ' | ' . $item->email;
        }

        return $data;
    }

    /**
     * @param null $type
     * @return false
     */
    public function getAppointmentInProgressing($type = NULL){
        $appointment = $this->appointments->where('status', Appointment::PROGRESSING_STATUS);
        if(!empty($type)){
            $appointment = $appointment->where('type', $type);
        }
        $appointment = $appointment->first();
        if(!empty($appointment)){
            return $appointment;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function checkServiceInProgressing(){
        foreach($this->memberServices as $service){
            if($service->status === MemberService::PROGRESSING_STATUS){
                return $service;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function checkCourseInProgressing(){
        foreach($this->memberCourses as $course){
            if($course->status === MemberCourse::PROGRESSING_STATUS){
                return $course;
            }
        }

        return false;
    }

    /**
     * @param $order_type
     * @return mixed
     */
    public function getDraftOrder($order_type){
        return $this->orders->where('status', Order::STATUS_DRAFT)
                            ->where('order_type', $order_type)
                            ->first();
    }

    /**
     * @return BelongsTo
     */
    public function type(){
        return $this->belongsTo(MemberType::class, 'type_id');
    }

    /**
     * @return HasMany
     */
    public function appointments(){
        return $this->hasMany(Appointment::class, 'member_id');
    }

    /**
     * @return HasMany
     */
    public function memberServices(){
        return $this->hasMany(MemberService::class);
    }

    /**
     * @return HasMany
     */
    public function memberCourses(){
        return $this->hasMany(MemberCourse::class);
    }

    /**
     * @return HasMany
     */
    public function orders(){
        return $this->hasMany(Order::class, 'member_id');
    }

}
