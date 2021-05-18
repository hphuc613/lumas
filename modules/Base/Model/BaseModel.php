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
     * @return array
     */
    public static function getArray($status = null){
        $query = self::query();
        if(!empty($status)){
            $query = $query->where('status', $status);
        }

        return $query->orderBy('name', 'asc')->pluck("name", "id")->toArray();
    }
}
