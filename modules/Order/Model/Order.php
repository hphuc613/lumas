<?php

namespace Modules\Order\Model;

use Illuminate\Database\Eloquent\Builder;
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
    protected $table = "orders";
    protected $primaryKey = "id";
    protected $guarded = [];

    public $timestamps = TRUE;

    const STATUS_DRAFT = 0;
    const STATUS_PAID = 1;
    const STATUS_ABORT = -1;


    const SERVICE_TYPE = 'service';
    const COURSE_TYPE = 'course';

    /**
     * @param $filter
     * @return Builder
     */
    public static function filter($filter){
        $query = self::query()->with('member')->with('creator');
        if(isset($filter['status'])){
            $query->where('status', $filter['status']);
        }
        if(isset($filter['code'])){
            $query->where('code', 'LIKE', '%' . $filter['code'] . '%');
        }
        if(isset($filter['month'])){
            $query->whereMonth('updated_at', $filter['month']);
        }
        if(isset($filter['member_id'])){
            $query->where('member_id', $filter['member_id']);
        }
        if(isset($filter['order_type'])){
            $query->where('order_type', $filter['order_type']);
        }
        if(isset($filter['creator'])){
            $query->where('created_by', $filter['creator']);
        }

        return $query;
    }

    /**
     * @return array
     */
    public static function getStatus(){
        return [
            self::STATUS_PAID  => trans("Paid"),
            self::STATUS_ABORT => trans("Abort"),
            self::STATUS_DRAFT => trans("Draft"),
        ];
    }

    /**
     * @return string
     */
    public function generateCode(){
        return 'LM-ORDER' . $this->member->id . 'T' . formatDate(time(), 'dmYHis');
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
    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsTo
     */
    public function updater(){
        return $this->belongsTo(User::class, 'updated_by');
    }


    /**
     * @return HasMany
     */
    public function orderDetails(){
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}
