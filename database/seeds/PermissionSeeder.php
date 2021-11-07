<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_permission_ids = []; // an empty array of stored permission IDs
        $user_permission_ids = [];
        $permissions_user_cant_access = ['UserController'];
        // iterate though all routes
        foreach (Route::getRoutes()->getRoutes() as $key => $route) {
            // get route action
            $action = $route->getActionname();
            $userCantAccess = false;
            foreach ($permissions_user_cant_access as $permission) {
                if (strpos($action, $permission)) {
                    $userCantAccess = true;
                }
            }
            // separating controller and method
            $_action = explode('@', $action);

            $controller = $_action[0];
            $method = end($_action);

            // check if this permission is already exists
            $permission_check = Permission::where(
                ['controller' => $controller, 'method' => $method]
            )->first();
            if (!$permission_check) {
                $permission = new Permission;
                $permission->controller = $controller;
                $permission->method = $method;
                $permission->save();
                // add stored permission id in array
                $admin_permission_ids[] = $permission->id;
                $user_permission_ids[] = $permission->id;
            }
        }
        // find admin role.
        $admin_role = Role::where('name', 'admin')->first();
        $user_role = Role::where('name', 'user')->first();
        // atache all permissions to admin role
        $admin_role->permissions()->attach($admin_permission_ids);
        $user_role->permissions()->attach($user_permission_ids);
    }
}
