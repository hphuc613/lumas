<?php

namespace Modules\Notification\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Base\Model\BaseModel;
use Modules\User\Model\User;

class NotificationModel extends BaseModel{
    protected $table = 'notifications';

    protected $primaryKey = 'id';

    protected $dates = ['deleted_at'];

    protected $guarded = [];

    /**
     * @return BelongsTo
     */
    public function users(){
        return $this->belongsTo(User::class);
    }
}
