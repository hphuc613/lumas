<?php

namespace Modules\Order\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Course\Model\Course;
use Modules\Service\Model\Service;
use Modules\Voucher\Model\CourseVoucher;
use Modules\Voucher\Model\ServiceVoucher;

/**
 * Class OrderDetail
 * @package Modules\Order\Model
 */
class OrderDetail extends Model{

    public $timestamps = TRUE;
    protected $table = "order_details";
    protected $primaryKey = "id";
    protected $guarded = [];

    /**
     * @return BelongsTo
     */
    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * @return BelongsTo
     */
    public function product(){
        if($this->order->order_type === 'service'){
            return $this->belongsTo(Service::class, 'product_id');
        }

        return $this->belongsTo(Course::class, 'product_id');
    }

    /**
     * @return BelongsTo
     */
    public function productVoucher(){
        if($this->order->order_type === 'service'){
            return $this->belongsTo(ServiceVoucher::class, 'voucher_id');
        }

        return $this->belongsTo(CourseVoucher::class, 'voucher_id');
    }
}
