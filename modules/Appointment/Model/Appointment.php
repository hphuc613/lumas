<?php

namespace Modules\Appointment\Model;

use App\AppHelpers\Helper;
use App\Notifications\Notification;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Model\BaseModel;
use Modules\Base\Model\Status;
use Modules\Course\Model\Course;
use Modules\Member\Model\Member;
use Modules\Member\Model\MemberServiceHistory;
use Modules\Service\Model\Service;
use Modules\Store\Model\Store;
use Modules\User\Model\User;

class Appointment extends BaseModel
{
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
     * @param array $options
     * @return bool|void
     */
    public function save(array $options = []) {
        parent::save($options);
        $this->afterSave();
    }

    /**
     * After Save Model
     */
    public function afterSave() {
        /** Add Notification*/
        if ($this->status == self::WAITING_STATUS) {
            $user         = User::find($this->user_id);
            $notification = $this->getNotification();
            if ($notification) {
                $data = $notification->data;
                $data = reset($data);
                if ((int)strtotime($this->time) > (int)strtotime($data['time'])) {
                    $data = [
                        'appointment_id' => (int)$this->id,
                        'title'          => $this->name,
                        'member'         => $this->member->name,
                        'member_id'      => (int)$this->member_id,
                        'user_id'        => (int)$this->user_id,
                        'time'           => $this->time,
                        'type'           => $this->type,
                        'status'         => Status::STATUS_PENDING,
                        'time_show'      => NULL,
                    ];
                    $notification->update(['data' => $data, 'read_at' => NULL]);
                }
            }
            else {
                $data = [
                    'appointment_id' => (int)$this->id,
                    'title'          => $this->name,
                    'member'         => $this->member->name,
                    'member_id'      => (int)$this->member_id,
                    'user_id'        => (int)$this->user_id,
                    'time'           => $this->time,
                    'type'           => $this->type,
                    'status'         => Status::STATUS_PENDING,
                    'time_show'      => NULL,
                ];
                $user->notify(new Notification($data));
            }
        }

        if ($this->status == self::ABORT_STATUS || !empty($this->deleted_at)) {
            if ($this->getNotification()) {
                $this->getNotification()->markAsRead();
            }
        }
    }

    /**
     * @return false|mixed
     */
    public function getNotification() {
        $notification = $this->user->notifications->where('data.appointment_id', $this->id)->first();
        if (!empty($notification)) {
            return $notification;
        }

        return false;
    }

    /**
     * @return string[]
     */
    public static function getTypeList() {
        return [self::SERVICE_TYPE => trans('Service'), self::COURSE_TYPE => trans('Course')];
    }

    /**
     * @return array
     */
    public static function getStatuses()
    : array {
        return [
            self::WAITING_STATUS   => trans('Waiting'), self::PROGRESSING_STATUS => trans('Progressing'),
            self::COMPLETED_STATUS => trans('Completed'), self::ABORT_STATUS => trans('Abort')
        ];
    }

    /**
     * @return array
     */
    public function getServiceList() {
        $data = Helper::isJson($this->service_ids, 1);
        $list = [];
        if ($data) {
            foreach ($data as $id) {
                $list[] = Service::find($id);
            }
        }

        return $list;
    }

    /**
     * @return array
     */
    public function getCourseList() {
        $data = Helper::isJson($this->course_ids, 1);
        $list = [];
        if ($data) {
            foreach ($data as $id) {
                $list[] = Course::find($id);
            }
        }

        return $list;
    }

    /**
     * @return string
     */
    public function getColorStatus() {
        $color = '#fec107';
        if ($this->status === self::WAITING_STATUS) {
            $color = '#03a9f3';
            if (strtotime($this->time) < time()) {
                $color = '#e46a76';
            }
        }
        elseif ($this->status === self::COMPLETED_STATUS) {
            $color = '#00c292';
        }
        elseif ($this->status === self::ABORT_STATUS) {
            $color = '#aaa';
        }

        return $color;
    }

    /**
     * @return BelongsTo
     */
    public function store() {
        return $this->belongsTo(Store::class, 'store_id');
    }

    /**
     * @return BelongsTo
     */
    public function member() {
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * @return HasMany
     */
    public function memberServiceHistory() {
        return $this->hasMany(MemberServiceHistory::class, 'appointment_id');
    }

    /**
     * @return BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
