<?php

namespace Modules\Member\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Model\BaseModel;
use Modules\User\Model\User;

class MemberServiceHistory extends BaseModel{
    use SoftDeletes;

    protected $table = "member_service_histories";

    protected $dates = ["deleted_at"];

    protected $guarded = [];

    public $timestamps = true;

    /**
     * @param $filter
     * @param $member_id
     * @param null $service_id
     * @return Builder
     */
    public static function filter($filter, $member_id, $service_id = null){
        $query = self::query()->with('memberService');
        $query->whereHas('memberService', function($ms_query) use ($member_id, $filter){
            if(isset($filter['code_history'])){
                $ms_query->where('code', $filter['code_history']);
            }
            if(isset($filter['code'])){
                $ms_query->where('code', $filter['code']);
            }
            if(!empty($service_id)){
                $ms_query->where('service_id', $service_id);
            }
            $ms_query->where('member_id', $member_id);
        });
        $query->orderBy('created_at', 'DESC');

        return $query;
    }

    /**
     * @return BelongsTo
     */
    public function memberService(){
        return $this->belongsTo(MemberService::class, "member_service_id");
    }

    /**
     * @return BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class, "updated_by");
    }
}
