<?php

namespace Modules\Member\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Member as BaseMember;

class Member extends BaseMember
{
    use SoftDeletes;

    protected $dates = ["deleted_at"];

    public $timestamps = true;

}
