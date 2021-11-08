<?php

namespace Modules\User\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Base\Model\BaseModel;
use Modules\Member\Model\MemberServiceHistory;
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
     * @param $filter
     * @return Builder
     */
    public static function filter($filter){
        $data = self::query()
                    ->join('users', 'users.id', '=', 'user_id')
                    ->select('users.name', 'salaries.*')
                    ->with(['user' => function($uq) use ($filter){
                        $uq->with('roles');
                    }]);

        if (isset($filter['role_id'])) {
            $data->whereHas('user', function($uq) use ($filter){
                $uq->whereHas('roles', function($qr) use ($filter){
                    $qr->where('role_id', $filter['role_id']);
                });
            });
        }
        if (isset($filter['name'])) {
            $data->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }
        if (isset($filter['month'])) {
            $month = formatDate(strtotime(Carbon::createFromFormat('m-Y', $filter['month'])), 'm/Y');
            $data->where('month', $month);
        }

        $data = $data->orderBy('month', 'desc')
                     ->orderBy('users.name', 'asc');
        return $data;
    }

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
        if (isset($this->month)) {
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
     * @param $get
     * @return float|int
     */
    public function getExtraBonusCommission($get = null){
        $extra_bonus = $this->service_rate ?? 0;
        $time        = time();
        if (isset($this->month)) {
            $time = strtotime(Carbon::createFromFormat('m/Y', $this->month));
        }

        $commission = MemberServiceHistory::query()
                                          ->join('member_services', function($join){
                                              $join->on('member_service_id', '=', 'member_services.id');
                                              $join->on('member_service_histories.updated_by', '=', 'member_services.created_by');
                                          })
                                          ->select('member_service_histories.*', 'member_services.price')
                                          ->whereMonth('start', formatDate($time, 'm-Y'))
                                          ->where('member_service_histories.updated_by', $this->user->id);

        $commission = $commission->sum('price');

        if ($get == 'total') {
            return $commission;
        }

        return $commission * $extra_bonus / 100;
    }

    /**
     * @return int
     */
    public function getTotalProvideServiceCommission(){
        $time = time();
        if (isset($this->month)) {
            $time = strtotime(Carbon::createFromFormat('m/Y', $this->month));
        }
        return MemberServiceHistory::query()
                                   ->join('member_services', function($join){
                                       $join->on('member_service_id', '=', 'member_services.id');
                                       $join->on('member_service_histories.updated_by', '<>', 'member_services.created_by');
                                   })
                                   ->whereMonth('member_service_histories.updated_at', formatDate($time, 'm-Y'))
                                   ->where('member_service_histories.updated_by', $this->user->id)
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
