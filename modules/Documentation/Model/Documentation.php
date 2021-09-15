<?php

namespace Modules\Documentation\Model;

use Illuminate\Database\Eloquent\Model;

class Documentation extends Model{
    protected $table = "documentations";

    protected $primaryKey = "id";

    protected $guarded = [];

    public $timestamps = true;

    const WEB_TYPE = 'WEB';

    const MOBILE_TYPE = 'MOBILE';

    const WEB_CT_TYPE = 'WEB_CT';

    const MOBILE_CT_TYPE = 'MOBILE_CT';

}
