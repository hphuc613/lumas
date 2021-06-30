<?php

namespace Modules\Documentation\Model;

use Illuminate\Database\Eloquent\Model;

class Documentation extends Model{
    protected $table = "documentations";

    protected $primaryKey = "id";

    protected $guarded = [];

    public $timestamps = true;
}
