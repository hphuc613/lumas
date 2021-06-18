<?php

namespace Modules\Order\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Member\Model\Member;
use Modules\User\Model\User;

/**
 * Class Order
 * @package Modules\Order\Model
 */
class Order extends Model{

    const STATUS_DRAFT = 0;
    const STATUS_PAID = 1;
    const STATUS_ABORT = -1;
    public $timestamps = TRUE;
    protected $table = "orders";
    protected $primaryKey = "id";
    protected $guarded = [];

    const SERVICE_TYPE = 'service';
    const COURSE_TYPE = 'course';

    /**
     * @return string
     */
    public function generateCode(){
        return 'LM-ORDER' . $this->member->id . 'T' . time();
    }

    /**
     * @return mixed
     */
    public function getTotalPrice(){
        return $this->orderDetails->sum('amount');
    }

    /**
     * @return BelongsTo
     */
    public function member(){
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * @return BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * @return HasMany
     */
    public function orderDetails(){
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}
