<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Modules\Permission\Model\Permission;
use Modules\Permission\Model\PermissionRole;

class AuthServiceProvider extends ServiceProvider{

    protected $permission_table = 'permissions';
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(){
        $this->registerPolicies();

        if (Schema::hasTable($this->permission_table)){
            $permissions = Permission::all();
            foreach ($permissions as $permission){
                Gate::define($permission->name, function ($user) use ($permission){
                    $role = $user->role;

                    return PermissionRole::checkRolePermission($permission->id, $role->id);
                });
            }
        }
    }
}
