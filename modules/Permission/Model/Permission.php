<?php

namespace Modules\Permission\Model;

use Modules\Base\Model\BaseModel;

/**
 * Class Permission
 * @package Modules\Permission\Model
 */
class Permission extends BaseModel{

    protected $table = 'permissions';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = TRUE;

    public static function filter($filter){
        $query = self::query();
        if (isset($filter['name'])){
            $query->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }

        return $query;
    }

    public static function getName($id){
        $data = self::find($id);

        return $data->name;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function child(){
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(){
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    /**
     * @return string
     */
    public static function getTableName(){
        return (new self())->getTable();
    }
}
