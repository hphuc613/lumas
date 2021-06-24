<?php

namespace Modules\Role\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Base\Model\BaseModel;

class CommissionRate extends BaseModel{

    protected $table = 'commission_rates';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = TRUE;

    /**
     * @return BelongsTo
     */
    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }

}
