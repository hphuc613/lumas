<?php

namespace Modules\PaymentMethod\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Model\BaseModel;

class PaymentMethod extends BaseModel{
    use SoftDeletes;

    protected $table = "payment_methods";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $guarded = [];

    public $timestamps = true;


}
