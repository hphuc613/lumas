<?php

namespace Modules\Member\Model;

use App\Member as BaseMember;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class Member extends BaseMember{
    use SoftDeletes;

    protected $dates = ["deleted_at"];

    public $timestamps = true;

    /**
     * @param $filter
     * @return Builder
     */
    public static function filter($filter){
        $query = self::query();
        if(isset($filter['name'])){
            $query->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }
        if(isset($filter['phone'])){
            $query->where('phone', 'LIKE', '%' . $filter['phone'] . '%');
        }
        if(isset($filter['email'])){
            $query->where('email', 'LIKE', '%' . $filter['email'] . '%');
        }
        if(isset($filter['status'])){
            $query->where('status', $filter['status']);
        }
        if(isset($filter['type_id'])){
            $query->where('type_id', $filter['type_id']);
        }

        return $query;
    }

    /**
     * @return string
     */
    public function getAvatar(){
        $avatar = $this->avatar;
        if(!empty($avatar) && File::exists(base_path() . '/storage/app/public/' . $avatar)){
            return url('/storage/' . $avatar);
        }
        return asset('/image/user.png');
    }

    /**
     * @return BelongsTo
     */
    public function type(){
        return $this->belongsTo(MemberType::class, 'type_id');
    }

}
