<?php

namespace Modules\Base\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CoreModuleCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The systems will create module with name you entered';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $module = ucfirst($this->argument('name'));

        if (!file_exists(base_path("modules/{$module}"))) {
            mkdir(base_path("modules/{$module}"), 0777, true);
            $this->config($module);
            $this->lang($module);
            $this->http($module);
            $this->model($module);
            $this->views($module);
        }

        Artisan::call('permissions:update');

        $this->info("Module {$module} has been created");
    }

    /**
     * Generate Config
     * @param $module
     */
    protected function config($module)
    {
        mkdir(base_path("modules/{$module}/Config"), 0777, true);
        //menu
        $content = "<?php
return [
    'name' => trans('" . $module . "'),
    'route' => route('get." . strtolower($module) . ".list'),
    'sort' => 1,
    'active'=> TRUE,
    'icon' => ' icon-menu',
    'middleware' => [],
    'group' => []
];";
        $fp = fopen(base_path("modules/{$module}/Config/menu.php"), "wb");
        fwrite($fp, $content);
        fclose($fp);

        //permission

        $content = "<?php
return [
    'name' => '" . strtolower($module) . "',
    'display_name' => trans('" . ucfirst($module) . "'),
    'group' => []
];";
        $fp = fopen(base_path("modules/{$module}/Config/permission.php"), "wb");
        fwrite($fp, $content);
        fclose($fp);
    }

    /**
     * Generate Multiple language
     * @param $module
     */
    protected function lang($module)
    {
        mkdir(base_path("modules/{$module}/Langs/en"), 0777, true);
        //lang file
        $content = "<?php
return [
    'name' => '" . $module . "',
];";
        $fp = fopen(base_path("modules/{$module}/Langs/en/language.php"), "wb");
        fwrite($fp, $content);
        fclose($fp);
    }

    /**
     * Generate Model
     * @param $module
     */
    protected function model($module)
    {
        mkdir(base_path("modules/{$module}/Model"), 0777, true);
        $content = '<?php

namespace Modules\\' . $module . '\\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ' . $module . ' extends Model
{
    use SoftDeletes;

    protected $table = "' . strtolower($module) . '";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $guarded = [];

    public $timestamps = true;


}
';
        $fp = fopen(base_path("modules/{$module}/Model/{$module}.php"), "wb");
        fwrite($fp, $content);
        fclose($fp);
    }

    /**
     * Generate Http folder
     * @param $module
     */
    protected function http($module)
    {
        $path_current = base_path() . '/modules/Base';
        mkdir(base_path("modules/{$module}/Http"), 0777, true);
        // Controller
        mkdir(base_path("modules/{$module}/Http/Controllers"), 0777, true);
        $content = '<?php

namespace Modules\\' . $module . '\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ' . $module . 'Controller extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }

    public function index(Request $request){
        return view("' . $module . '::index");
    }
}
';
        $fp = fopen(base_path("modules/{$module}/Http/Controllers/{$module}Controller.php"), "wb");
        fwrite($fp, $content);
        fclose($fp);
        /************************************************************************************/

        //Validation
        mkdir(base_path("modules/{$module}/Http/Requests"), 0777, true);
        $content = '<?php

namespace Modules\\' . $module . '\Http\Requests;

use App\AppHelpers\Helper;
use Illuminate\Foundation\Http\FormRequest;

class ' . $module . 'Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = Helper::segment(2);
        switch ($method) {
            default:
                return [];
                break;
            case "update":
                return [];
                break;
        }
    }

    public function messages()
    {
        return [];
    }

    public function attributes()
    {
        return [];
    }
}
';

        $fp = fopen(base_path("modules/{$module}/Http/Requests/{$module}Request.php"), "wb");
        fwrite($fp, $content);
        fclose($fp);

        /*****************************************************************************************/

        //Route
        mkdir(base_path("modules/{$module}/Http/Routes"), 0777, true);

        $content = '<?php
use Illuminate\Support\Facades\Route;

Route::prefix("' . strtolower($module) . '")->group(function (){
    Route::get("/", "' . $module . 'Controller@index")->name("get.' . strtolower($module) . '.list");
});
';
        $fp = fopen(base_path("modules/{$module}/Http/Routes/admin.php"), "wb");
        fwrite($fp, $content);
        fclose($fp);

        $content2 = '<?php
use Illuminate\Support\Facades\Route;
';
        $fp = fopen(base_path("modules/{$module}/Http/Routes/web.php"), "wb");
        fwrite($fp, $content2);
        fclose($fp);

    }

    protected function views($module)
    {
        mkdir(base_path("modules/{$module}/Views"), 0777, true);
        $content = '@extends("Base::layouts.master")

@section("content")
    <div id="' . strtolower($module) . '-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("' . $module . '") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("' . $module . ' Listing") }}</h3></div>
            <div class="group-btn">
                <a href="#" class="btn btn-main-color"><i class="fa fa-plus"></i> &nbsp; {{ trans("Add New") }}</a>
            </div>
        </div>
    </div>
    <!--Search box-->
    <div class="search-box">
        <div class="card">
            <div class="card-header" data-toggle="collapse" data-target="#form-search-box" aria-expanded="false" aria-controls="form-search-box">
                <div class="title">{{ trans("Search") }}</div>
            </div>
            <div class="card-body collapse show" id="form-search-box">
                <form action="" method="get">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="text-input">{{ trans("' . $module . ' name") }}</label>
                                <input type="text" class="form-control" id="text-input" name="name" value="">
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <button type="submit" class="btn btn-main-color mr-2">{{ trans("Search") }}</button>
                        <button type="button" class="btn btn-default clear">{{ trans("Cancel") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="listing">
        <div class="card">
            <div class="card-body">
                <div class="sumary">
                    <span class="listing-information">
                        <!-- Quantity item -->
                        </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th width="50px">#</th>
                            <th>{{ trans("Search") }}</th>
                            <th width="200px" class="action">{{ trans("Search") }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- listing -->
                        </tbody>
                    </table>
                    <div class="mt-5 pagination-style">
                        <!-- Pagination -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection';

        $fp = fopen(base_path("modules/{$module}/Views/index.blade.php"), "wb");
        fwrite($fp, $content);
        fclose($fp);

    }

    /*protected function file($module){
        $content = '';
        $fp      = fopen(base_path("packages/{$module}/composer.json"), "wb");
        fwrite($fp, $content);
        fclose($fp);
        $fp = fopen(base_path("packages/{$module}/module.json"), "wb");
        fwrite($fp, $content);
        fclose($fp);
        $fp = fopen(base_path("packages/{$module}/README.md"), "wb");
        fwrite($fp, $content);
        fclose($fp);
    }*/
}
