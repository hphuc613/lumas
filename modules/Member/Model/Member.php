<?php

namespace Modules\Member\Model;

use App\AppHelpers\Helper;
use App\Member as BaseMember;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class Member extends BaseMember{
    use SoftDeletes;

    protected $dates = ["deleted_at"];

    public $timestamps = true;

    /**
     * @return string
     */
    public function getAvatar(){
        $contact_info = $this->contact_info;
        if(Helper::isJson($contact_info)){
            $contact_info = json_decode($this->contact_info);
        }
        $avatar = $contact_info->avatar ?? null;
        if(!empty($avatar) && File::exists(base_path() . '/storage/app/public/' . $avatar)){
            return url('/storage/' . $avatar);
        }
        return asset('/image/user.png');
    }

}
