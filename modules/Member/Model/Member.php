<?php

namespace Modules\Member\Model;

use App\Member as BaseMember;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Modules\Base\Model\Status;

class Member extends BaseMember{
    use SoftDeletes;

    protected $dates = ["deleted_at"];

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

    /**
     * @param null $status
     * @return array
     */
    public static function getArray($status = null){
        $query = self::select('id', 'name', 'phone', 'email');
        if(!empty($status)){
            $query = $query->where('status', $status);
        }
        $query = $query->orderBy('name', 'asc')->get();

        $data = [];

        foreach($query as $item){
            $data[$item->id] = $item->name . ' | ' . $item->phone . ' | ' . $item->email;
        }

        return $data;
    }

}
