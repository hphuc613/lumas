<?php

namespace Modules\Setting\Model;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = "settings";

    protected $primaryKey = "id";

    protected $guarded = [];

    public $timestamps = false;

    /**
     * @param $key
     * @return mixed
     */
    public static function getValueByKey($key)
    {
        $setting = self::where('key', $key)->first();

        if (!empty($setting)){
            return $setting->value;
        }

        return NULL;
    }
}
