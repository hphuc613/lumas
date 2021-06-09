<?php

namespace Modules\Base\Console;

use App\AppHelpers\Helper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Permission\Model\Permission;
use Modules\Permission\Model\PermissionRole;
use Modules\Role\Model\Role;

class PermissionCommand extends Command{

    protected $list_permission = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The systems will setup Role Module';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(){
        $permissions = Helper::config_permission_merge();

        DB::table('permissions')->delete();
        $statement = "ALTER TABLE permissions AUTO_INCREMENT = 1;";
        DB::unprepared($statement);
        /** Insert permission list */
        foreach($permissions as $value){
            if(!isset($value['name'])){
                foreach($value as $item){
                    self::updatePermission($item);
                }
            }else{
                self::updatePermission($value);
            }
        }

        /** Delete permission not exist*/
        $permission_del = array_diff(Permission::pluck('name')->toArray(), $this->list_permission);
        Permission::whereIn('name', $permission_del)->delete();

        $db_permissions = Permission::all();
        $admin_role     = Role::getAdminRole();

        $permission_roles       = PermissionRole::query();
        $permission_role_admin  = clone $permission_roles;
        $permission_role_others = clone $permission_roles;

        /** Delete permission id not exist */
        $permission_role_other_list = $permission_role_others->where('role_id', '<>', $admin_role->id)
                                                             ->groupBy('permission_id')
                                                             ->get();

        foreach($permission_role_other_list as $value){
            if(empty($value->permission)){
                $permission_role_others->where('permission_id', $value->permission_id)
                                       ->where('role_id', $value->role_id)
                                       ->delete();
            }
        }

        /** Update permission for administrator id */
        $permission_role_admin->where('role_id', $admin_role->id)->delete();
        foreach($db_permissions as $permission){
            $permission_role                = new PermissionRole;
            $permission_role->permission_id = $permission->id;
            $permission_role->role_id       = $admin_role->id;
            $permission_role->save();
        }

        $this->info('Update Permission Successfully');
    }

    /**
     * @param $value
     */
    public function updatePermission($value){
        $data                              = [
            'name'         => trim($value['name']),
            'display_name' => ucwords($value['display_name']),
            'parent_id'    => 0,
        ];
        $group                             = Permission::firstOrCreate($data);
        $this->list_permission[$group->id] = $group->name;
        if(isset($value['group']) && count($value['group']) > 0){
            foreach($value['group'] as $subs => $sub){
                $child = Permission::firstOrCreate([
                    'name'         => trim($sub['name']),
                    'display_name' => ucwords($sub['display_name']),
                    'parent_id'    => $group->id,
                ], $sub);

                $this->list_permission[$child->id] = $child->name;
            }
        }
    }
}
