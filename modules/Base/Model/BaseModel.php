<?php
namespace Modules\Base\Model;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model{

    /**
     * @param null $status
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection|\Modules\Base\Model\BaseModel[]
     */
    public static function getArray($status = NULL){
        $query = self::select('id','name');
        if (!empty($status)){
            $query = $query->where('status',$status);
        }
        $query = $query->orderBy('name', 'asc')->get();

        $data = [];

        foreach ($query as $item){
            $data[$item->id] = $item->name;
        }

        return $data;
    }

}
