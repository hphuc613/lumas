<?php

namespace Modules\User\Model;

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
    public function getSaleCommission(){
        if($this->user->getTargetBy() === CommissionRateSetting::PERSON_INCOME){
            $rate       = $this->user->getCommissionRate();
            $commission = $this->user->orders()
                                     ->whereMonth('updated_at', formatDate(time(), 'm'))
                                     ->sum('total_price');

            return $commission * $rate / 100;
        }

        return 0;
    }

    /**
     * @return float|int
     */
    public function getServiceCommission(){
        if($this->user->getTargetBy() === CommissionRateSetting::PERSON_INCOME){
            $rate                     = $this->user->getCommissionRate();
            $member_service_histories = MemberServiceHistory::query()
                                                            ->whereMonth('updated_at', formatDate(time(), 'm'))
                                                            ->where('updated_by', $this->user->id)
                                                            ->get();
            $data                     = 0;
            foreach($member_service_histories as $history){
                $data += $history->memberService->price;
            }

            return $data * $rate / 100;
        }

        return 0;
    }


    /**
     * @return float|int
     */
    public
    function getCompanyIncomeCommission(){
        if($this->user->getTargetBy() === CommissionRateSetting::COMPANY_INCOME){
            $rate       = $this->user->getCommissionRate();
            $commission = Order::query()
                               ->whereMonth('updated_at', formatDate(time(), 'm'))
                               ->sum('total_price');

            return $commission * $rate / 100;
        }

        return 0;
    }

    /**
     * @return float|int
     */
    public
    function getTotalCommission(){
        $sale_commission    = $this->getSaleCommission();
        $service_commission = $this->getServiceCommission();
        $company_commission = $this->getCompanyIncomeCommission();
        return $sale_commission + $service_commission + $company_commission;
    }
}
