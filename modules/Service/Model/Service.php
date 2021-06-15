<?php

namespace Modules\Service\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Model\BaseModel;
use Modules\Base\Model\Status;
use Modules\Member\Model\MemberService;
use Modules\Voucher\Model\ServiceVoucher;

/**
 * Class Service
 * @package Modules\Service\Model
 */
class Service extends BaseModel{
    use SoftDeletes;

    protected $table = "services";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $guarded = [];

    public $timestamps = true;

    /**
     * @param $filter
     * @return Builder
     */
    public static function filter($filter){
        $query = self::with('type');
        $query = $query->whereHas('type', function($query){
            return $query->where('status', Status::STATUS_ACTIVE);
        });
        if(isset($filter['name'])){
            $query->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }
        if(isset($filter['type_id'])){
            $query->where('type_id', $filter['type_id']);
        }

        return $query;
    }

    /**
     * @param null $status
     * @param false $in
     * @param false $not_in
     * @return array
     */
    public static function getArray($status = null, $in = false, $not_in = false){
        $query = self::query()->select('id', 'name', 'price');
        if(!empty($status)){
            $query = $query->where('status', $status);
        }
        $query = $query->orderBy('name')->get();

        $data = [];

        foreach($query as $item){
            $data[$item->id] = $item->name . ' | ' . $item->price;
        }

        return $data;
    }

    /**
     * @return BelongsTo
     */
    public function type(){
        return $this->belongsTo(ServiceType::class);
    }

    /**
     * @return HasMany
     */
    public function vouchers(){
        return $this->hasMany(ServiceVoucher::class, 'service_id');
    }

    /**
     * @return HasMany
     */
    public function memberService(){
        return $this->hasMany(MemberService::class);
    }
}
