<?php

namespace Modules\Member\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
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
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function filter($filter, $member_id){
        $query = self::query();
        if(isset($filter['code'])){
            $query->where('code', $filter['code']);
            $query->orWhereHas('service', function($sq) use ($filter){
                $sq->where('name', 'LIKE', '%' . $filter['code'] . '%');
            });
        }
        $query->where('member_id', $member_id)
              ->whereRaw('deduct_quantity < quantity')
              ->orderBy("created_at", "DESC");

        return $query;
    }

    /**
     * @param $filter
     * @param $member_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function filterCompleted($filter, $member_id){
        $query = self::query();
        if(isset($filter['code_completed'])){
            $query->where('code', $filter['code_completed']);
            $query->orWhereHas('service', function($sq) use ($filter){
                $sq->where('name', 'LIKE', '%' . $filter['code_completed'] . '%');
            });
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
        return $this->belongsTo(Voucher::class, "voucher_id");
    }

}
