<?php

namespace Modules\Base\Model;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Status
 * @package Modules\Base\Model
 */
class Status extends Model{

    const STATUS_INACTIVE = -1;
    const STATUS_PENDING  = 0;
    const STATUS_ACTIVE   = 1;

    /**
     * @param $status
     * @return array|Application|Translator|string
     */
    public static function getStatus($status){
        $name = '';
        switch($status){
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

    /**
     * @return array
     */
    public static function getStatuses(){
        return [
            self::STATUS_ACTIVE   => trans('Active'),
            self::STATUS_INACTIVE => trans('Inactive')
        ];
    }
}
