<?php

namespace Modules\Member\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Modules\Base\Model\BaseModel;
use Modules\Service\Model\Service;
use Modules\Voucher\Model\ServiceVoucher;

class MemberService extends BaseModel{
    use SoftDeletes;

    protected $table = "member_services";

    protected $dates = ["deleted_at"];

    protected $guarded = [];

    public $timestamps = true;

    const COMPLETED_STATUS = 1;

    const PROGRESSING_STATUS = 0;


    /**
     * @param $filter
     * @param $member_id
     * @return Builder
     */
    public static function filter($filter, $member_id){
        $query = self::query();
        if(isset($filter['code'])){
            $query->where('code', 'LIKE', '%' . $filter['code'] . '%');
        }
        if(isset($filter['service_search'])){
            $query->where('service_id', $filter['service_search']);
        }
        $query->where('member_id', $member_id)
              ->whereRaw('deduct_quantity < quantity')
              ->orderBy("created_at", "DESC");

        return $query;
    }

    /**
     * @param $filter
     * @param $member_id
     * @return Builder
     */
    public static function filterCompleted($filter, $member_id){
        $query = self::query();
        if(isset($filter['code_completed'])){
            $query->where('code', $filter['code_completed']);
        }
        if(isset($filter['service_search_completed'])){
            $query->where('service_id', $filter['service_search_completed']);
        }
        $query->where('member_id', $member_id)
              ->whereRaw('deduct_quantity = quantity')
              ->orderBy("created_at", "DESC");

        return $query;
    }

    /**
     * @return string[]
     */
    public static function getStatus(){
        return [
            self::COMPLETED_STATUS   => trans('Finish'),
            self::PROGRESSING_STATUS => trans('Using')
        ];
    }

    /**
     * @param $member_id
     * @param false $search_history
     * @return array
     */
    public static function getArrayByMember($member_id, $search_history = false){
        $query = self::query()->where('member_id', $member_id);
        if($search_history){
            $query->whereRaw('deduct_quantity = quantity');
        }else{
            $query->whereRaw('deduct_quantity < quantity');
        }
        $query = $query->get();
        $data  = [];
        foreach($query as $value){
            $data[$value->service_id] = $value->service->name ?? "N/A";
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public function getRemaining(){
        return $this->quantity - $this->deduct_quantity;
    }

    /**
     * @return string
     */
    public function generateCode(){
        return 'LMC' . $this->member->id . 'SV' . $this->service->id . 'T' . time();
    }

    /**
     * @param $data
     * @param $appointment
     */
    public function eSign($data, $appointment){
        if(empty($data['signature'])){
            $data['signature'] = Auth::user()->name;
        }

        $data['start']             = $this->updated_at;
        $data['end']               = formatDate(time(), 'Y-m-d H:i:s');
        $data['member_service_id'] = $this->id;
        $data['appointment_id']    = $appointment->id;
        $data['updated_by']        = Auth::id();
        $history                   = new MemberServiceHistory($data);
        $history->save();
    }


    /**
     * @param $data
     * @return bool
     */
    public static function insertData($data){
        $member_service = self::query()
                              ->where('member_id', $data['member_id'])
                              ->where('service_id', $data['service_id'])
                              ->where('voucher_id', $data['voucher_id'])
                              ->where('price', $data['price'])
                              ->whereRaw('deduct_quantity < quantity')
                              ->first();

        if(empty($member_service)){
            $member_service        = new MemberService($data);
            $member_service->code  = $member_service->generateCode();
            $member_service->price =
                !empty($member_service->voucher_id) ? $member_service->voucher->price : $member_service->service->price;
            $member_service->save();
        }else{

            /** Check when no Voucher */
            if(empty($member_service->voucher_id)){
                $check_same_price = (int)$member_service->price === (int)$member_service->service->price;
            }else{
                $voucher          = $member_service->voucher;
                $check_same_price = (int)$voucher->price === (int)$member_service->price;
            }

            if($check_same_price){
                $data['quantity'] = (int)$member_service->quantity + (int)$data['quantity'];
                $member_service->update($data);
            }else{
                $member_service        = new MemberService($data);
                $member_service->code  = $member_service->generateCode();
                $member_service->price =
                    !empty($member_service->voucher_id) ? $member_service->voucher->price : $member_service->service->price;
                $member_service->save();
            }
        }

        return true;
    }

    /**
     * @return BelongsTo
     */
    public function member(){
        return $this->belongsTo(Member::class, "member_id");
    }

    /**
     * @return BelongsTo
     */
    public function service(){
        return $this->belongsTo(Service::class, "service_id");
    }

    /**
     * @return BelongsTo
     */
    public function voucher(){
        return $this->belongsTo(ServiceVoucher::class, "voucher_id");
    }

    /**
     * @return HasMany
     */
    public function histories(){
        return $this->hasMany(MemberServiceHistory::class, "member_service_id");
    }

}
