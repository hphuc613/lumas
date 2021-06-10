<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){
        Schema::defaultStringLength(191);

        /**
         * Validation Re-enter Password
         */
        Validator::extend('re_enter_password', function($attribute, $value, $parameters, $validator){
            $data = $validator->getData();
            if(empty($data['password']) || $value === $data['password']){
                return true;
            }

            return false;
        });

        /**
         * Validate Unique Custom
         */
        Validator::extend('validate_unique', function($attribute, $value, $parameters, $validator){
            if(!empty($parameters)){
                $table = reset($parameters);
                $id    = (int)array_pop($parameters);
                if($id !== $table && is_numeric($id)){
                    $result      = DB::select(DB::raw("SHOW KEYS FROM {$table} WHERE Key_name = 'PRIMARY'"));
                    $primary_key = $result[0]->Column_name;
                    $query       = DB::table($table)->where($attribute, $value)->where($primary_key, '<>',
                        $id)->where('deleted_at',
                        null)->exists();
                }else{
                    $query = DB::table($table)->where($attribute, $value)->where('deleted_at', null)->exists();
                }

                if(!$query){
                    return true;
                }
            }

            return false;
        });

        /**
         * Check exist Injection
         */
        Validator::extend('check_exist', function($attribute, $value, $parameters, $validator){
            $table           = reset($parameters);
            $check_attribute = array_pop($parameters);
            $query           = DB::table($table)->where($check_attribute, $value)->where('deleted_at', null)->exists();

            if($query){
                return true;
            }

            return false;
        });

        /**
         * Check date in the past
         */
        Validator::extend('check_past', function($attribute, $value, $parameters, $validator){
            if(strtotime($value) < strtotime(formatDate(time(), 'd-m-Y H:i'))){
                return false;
            }
            return true;
        });
    }
}
