<?php

namespace Modules\User\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Base\Model\BaseModel;
use Modules\Member\Model\MemberServiceHistory;
use Modules\Order\Model\Order;
use Modules\Setting\Model\CommissionRateSetting;

/**
 * Class Salary
 * @package Modules\User\Model
 */
class Salary extends BaseModel{

    protected $table = 'salaries';

    protected $primaryKey = "id";

    protected $guarded = [];

    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return float|int
     */
    public function getTotalSalary(){
        $basic_salary     = $this->basic_salary;
        $total_commission = $this->getTotalCommission();

        return $basic_salary + $total_commission;
    }

    /**
     * @return float|int
     */
    public function getPaymentRateOrBonusCommission(){
        $rate = $this->payment_rate;
        $time = time();
        if (isset($this->month)){
            $time = strtotime(Carbon::createFromFormat('m/Y', $this->month));
        }
        if ($this->user->getTargetBy() === CommissionRateSetting::PERSON_INCOME) {
            $commission = $this->user->orders()
                                     ->whereMonth('updated_at', formatDate($time, 'm'))
                                     ->sum('total_price');

            return $commission * $rate / 100;
        }

        return $rate;
    }

    /**
     * @return float|int
     */
    public function getExtraBonusCommission(){
        $extra_bonus = $this->service_rate ?? 0;
        $time = time();
        if (isset($this->month)){
            $time = strtotime(Carbon::createFromFormat('m/Y', $this->month));
        }
        if ($this->user->getTargetBy() === CommissionRateSetting::PERSON_INCOME) {
            $commission = $this->user->orders()
                                     ->whereMonth('updated_at', formatDate($time, 'm'))
                                     ->sum('total_price');

            return $commission * $extra_bonus / 100;
        }
        $commission = Order::query()
                           ->whereMonth('updated_at', formatDate($time, 'm'))
                           ->sum('total_price');

        return $commission * $extra_bonus / 100;
    }

    /**
     * @return float|int
     */
    public function getTotalProvideServiceCommission(){
        $time = time();
        if (isset($this->month)){
            $time = strtotime(Carbon::createFromFormat('m/Y', $this->month));
        }
        return MemberServiceHistory::query()
                                   ->whereMonth('updated_at', formatDate($time, 'm'))
                                   ->where('updated_by', $this->user->id)
                                   ->count();
    }

    /**
     * @return float|int
     */
    public function getTotalCommission(){
        $service_pay        = CommissionRateSetting::getValueByKey(CommissionRateSetting::SERVICE_PAY);
        $sale_commission    = $this->getPaymentRateOrBonusCommission();
        $extra_bonus        = $this->getExtraBonusCommission();
        $service_commission = $this->getTotalProvideServiceCommission();

        return $sale_commission + $extra_bonus + ($service_commission * $service_pay);
    }
}
