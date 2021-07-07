<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider{

    public function boot(){
        $path       = base_path() . '/modules';
        $listModule = array_map('basename', File::directories($path));
        foreach($listModule as $module){
            $module_path = $path . '/' . $module;
            $namespace   = 'Modules\\' . $module;

            Route::group(['module' => $module, 'namespace' => $namespace . '\Http\Controllers'],
                function() use ($module_path, $module){
                    /** Route base */
                    $route_path = $module_path . '/Http/Routes/route.php';
                    if(file_exists($route_path)){
                        $this->loadRoutesFrom($route_path);
                    }

                    /** Route api */
                    $route_path = $module_path . '/Http/Routes/api.php';
                    if(file_exists($route_path)){
                        $this->loadRoutesFrom($route_path);
                    }

                    /** Route admin */
                    $route_path_admin = $module_path . '/Http/Routes/admin.php';
                    if(file_exists($route_path_admin)){
                        $this->loadRoutesFrom($route_path_admin);
                    }

                    /** Route web */
                    $route_path_web = $module_path . '/Http/Routes/web.php';
                    if(file_exists($route_path_web)){
                        $this->loadRoutesFrom($route_path_web);
                    }
                });
            if(is_dir($module_path . '/Langs')){
                //Multiple language php file
                $this->loadTranslationsFrom($module_path . "/Langs", $module);
                //Multiple language file json
                $this->loadJSONTranslationsFrom($module_path . '/Langs');
            }

            if(File::exists($module_path . "/Helper")){
                // Tất cả files có tại thư mục helpers
                $helper_dir = File::allFiles($module_path . "/Helper");
                // khai báo helpers
                foreach($helper_dir as $key => $value){
                    $file = $value->getPathName();
                    require $file;
                }
            }

            if(is_dir($module_path . '/Views')){
                $this->loadViewsFrom($module_path . '/Views', $module);
            }
        }
    }

    public function register(){

    }
}
