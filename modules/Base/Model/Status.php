<?php

namespace Modules\Base\Model;

use Illuminate\Database\Eloquent\Model;

class Status extends Model{

    const STATUS_INACTIVE = -1;
    const STATUS_PENDING = 0;
    const STATUS_ACTIVE = 1;

    const STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'Inactive'
    ];

    public static function getStatus($status){
        $name = '';
        switch ($status){
            case self::STATUS_ACTIVE:
                $name = trans('Active');
                break;
            case self::STATUS_INACTIVE:
                $name = trans('Inactive');
                break;
            case self::STATUS_PENDING:
                $name = trans('Pending');
                break;
        }

        return $name;
    }
}
