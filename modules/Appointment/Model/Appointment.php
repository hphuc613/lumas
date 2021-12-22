<?php

namespace Modules\Appointment\Model;

use App\AppHelpers\Helper;
use App\Notifications\Notification;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Modules\Base\Model\BaseModel;
use Modules\Base\Model\Status;
use Modules\Course\Model\Course;
use Modules\Instrument\Model\Instrument;
use Modules\Member\Model\Member;
use Modules\Member\Model\MemberServiceHistory;
use Modules\Notification\Model\NotificationModel;
use Modules\Room\Model\Room;
use Modules\Service\Model\Service;
use Modules\Store\Model\Store;
use Modules\User\Model\User;

class Appointment extends BaseModel{
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


    public static function filter($filter){
        $query = Appointment::query()
                            ->with('member')
                            ->with('store')
                            ->with('user');
        if (isset($filter['name'])) {
            $query->where('name', $filter['name']);
        }
        if (isset($filter['member_id'])) {
            $query->where('member_id', $filter['member_id']);
        }
        if (isset($filter['status'])) {
            $query->where('status', $filter['status']);

        }
        if (isset($filter['store_id'])) {
            $query->where('store_id', $filter['store_id']);
        }
        if (isset($filter['month'])) {
            $time = explode('-', $filter['month']);
            $query->whereMonth('time', $time[0])
                  ->whereYear('time', $time[1]);
        }
        if (isset($filter['type'])) {
            $query->where('type', $filter['type']);
        }

        return $query;
    }


    public static function boot(){
        parent::boot();

        static::saved(function($model){
            $model->addNotification($model->user_id);
            foreach($model->staffs as $staff) {
                $model->addNotification($staff->id);
            }

            NotificationModel::query()
                             ->where('data->appointment_id', $model->id)
                             ->where('notifiable_id', '<>', $model->user_id)
                             ->whereNotIn('notifiable_id', $model->staffs->pluck('id')->toArray())
                             ->delete();
        });
    }


    /**
     * @param null $user_id
     */
    public function addNotification($user_id){
        /** Add Notification*/
        $user = User::query()->find($user_id);
        if ($this->status == self::WAITING_STATUS) {
            $notification = $this->getNotification($user);
            if ($notification) {
                if ((int)strtotime($this->time) > time()) {
                    $data = [
                        'appointment_id'   => (int)$this->id,
                        'title'            => $this->name,
                        'member'           => $this->member->name,
                        'member_id'        => (int)$this->member_id,
                        'user_id'          => (int)$this->user_id,
                        'time'             => $this->time,
                        'type'             => $this->type,
                        'status'           => Status::STATUS_PENDING,
                        'time_show'        => NULL,
                        'user_time_show'   => NULL,
                        'user_read_at'     => NULL,
                        'client_read_at'   => NULL,
                        'client_time_show' => NULL,
                    ];
                    $notification->update(['data' => $data, 'read_at' => NULL]);
                }
            } else {
                $data = [
                    'appointment_id'   => (int)$this->id,
                    'title'            => $this->name,
                    'member'           => $this->member->name,
                    'member_id'        => (int)$this->member_id,
                    'user_id'          => (int)$this->user_id,
                    'time'             => $this->time,
                    'type'             => $this->type,
                    'status'           => Status::STATUS_PENDING,
                    'time_show'        => NULL,
                    'user_time_show'   => NULL,
                    'user_read_at'     => NULL,
                    'client_read_at'   => NULL,
                    'client_time_show' => NULL,
                ];
                $user->notify(new Notification($data));
            }
        }

        if ($this->status == self::ABORT_STATUS || !empty($this->deleted_at)) {
            if ($this->getNotification($user)) {
                $this->getNotification($user)->markAsRead();
            }
        }
    }

    /**
     * @param $user
     * @return bool
     */
    public function getNotification($user){
        $notification = $user->notifications->where('data.appointment_id', $this->id)->first();

        if (!empty($notification)) {
            return $notification;
        }

        return false;
    }

    /**
     * @return string[]
     */
    public static function getTypeList(){
        return [self::SERVICE_TYPE => trans('Service'), self::COURSE_TYPE => trans('Course')];
    }

    /**
     * @return array
     */
    public static function getStatuses(){
        return [
            self::WAITING_STATUS     => trans('Waiting'),
            self::PROGRESSING_STATUS => trans('Progressing'),
            self::COMPLETED_STATUS   => trans('Completed'),
            self::ABORT_STATUS       => trans('Abort')
        ];
    }

    /**
     * @return Collection
     */
    public function getServiceList(){
        $data = Helper::isJson($this->service_ids, 1);
        $list = [];
        if ($data) {
            foreach($data as $id) {
                $service = Service::find($id);
                if (!empty($service)) {
                    $list[] = $service;
                }
            }
        }

        return collect($list);
    }

    /**
     * @return int
     */
    public function getTotalIntendTimeService(){
        $total = 0;
        if (is_string($this->service_ids)) {
            $this->service_ids = $this->getServiceList();
        }
        foreach($this->service_ids as $val) {
            if (!empty($val)) {
                $total = $total + (int)$val->intend_time;
            }
        }

        return $total;
    }

    /**
     * @return array
     */
    public function getCourseList(){
        $data = Helper::isJson($this->course_ids, 1);
        $list = [];
        if ($data) {
            foreach($data as $id) {
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
        if ($this->status === self::WAITING_STATUS) {
            $color = '#03a9f3';
            if (strtotime($this->time) < time()) {
                $color = '#e46a76';
            }
        } elseif ($this->status === self::COMPLETED_STATUS) {
            $color = '#00c292';
        } elseif ($this->status === self::ABORT_STATUS) {
            $color = '#aaa';
        }

        return $color;
    }

    /**
     * @return bool
     */
    public function checkProgressing(){
        if ($this->status === self::PROGRESSING_STATUS) {
            return true;
        }

        return false;
    }

    /**
     * @param bool $get_name
     * @return array
     */
    public function getRooms($get_name = false){
        $ids = json_decode($this->room_id, true) ?? [];
        if (is_numeric($ids)) {
            $ids = [$ids];
        }

        $data = Room::query()->whereIn('id', $ids);
        if ($get_name) {
            $data = $data->pluck('name');
        } else {
            $data = $data->pluck('id');
        }

        return $data->toArray();
    }

    /**
     * @param bool $get_name
     * @return array
     */
    public function getInstruments($get_name = false){
        $ids = json_decode($this->instrument_id, true) ?? [];
        if (is_numeric($ids)) {
            $ids = [$ids];
        }
        $data = Instrument::query()->whereIn('id', $ids);
        if ($get_name) {
            $data = $data->pluck('name');
        } else {
            $data = $data->pluck('id');
        }

        return $data->toArray();
    }

    /**
     * @return array
     */
    public function getAssign(){
        $assign        = json_decode($this->assign, 1);
        $data          = [];
        foreach($assign as $staff_id => $item) {
            if (!empty($item['service_id'])) {
                $staff                           = User::query()->find($staff_id);
                $service                         = Service::query()->find($item['service_id']);
                $data[$staff_id]['staff_id']     = $staff->id;
                $data[$staff_id]['staff_name']   = $staff->name;
                $data[$staff_id]['service_id']   = $service->id;
                $data[$staff_id]['service_name'] = $service->name;
                $data[$staff_id]['time']         = $item['time'];
            }
        }
        if (!empty($data)){
            $data['check'] = true;
        }

        return $data;
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
     * @return HasMany
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

    /**
     * @return BelongsToMany
     */
    public function staffs(){
        return $this->belongsToMany(User::class, 'appointment_staff', 'appointment_id', 'user_id');
    }
}
