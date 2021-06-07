<?php

namespace Modules\Member\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Modules\Base\Model\BaseModel;
use Modules\Service\Model\Service;
use Modules\Voucher\Model\Voucher;

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
            self::COMPLETED_STATUS   => 'Completed',
            self::PROGRESSING_STATUS => 'Progressing'
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
            $data[$value->service_id] = $value->service->name;
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
    public function generateCode() {
        return 'LMC' . $this->member->id . 'SV' . $this->service->id . 'T' . time();
    }

    /**
     * @param $data
     * @param $appointment
     */
    public function eSign($data, $appointment) {
        if (empty($data['signature'])) {
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
     * @return BelongsTo
     */
    public function member() {
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
        return $this->belongsTo(Voucher::class, "voucher_id");
    }

}
