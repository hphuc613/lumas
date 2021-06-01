<?php

namespace Modules\Base\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 * @package Modules\Base\Model
 */
class BaseModel extends Model{
    /**
     * @param null $status
     * @param false $in
     * @param false $not_in
     * @return array
     */
    public static function getArray($status = null, $in = false, $not_in = false){
        $query = self::query();
        if(!empty($status)){
            $query = $query->where('status', $status);
        }
        if($in){
            $query = $query->whereIn('id', $in);
        }
        if($not_in){
            $query = $query->whereNotIn('id', $not_in);
        }

        return $query->orderBy('name', 'asc')->pluck("name", "id")->toArray();
    }
}
