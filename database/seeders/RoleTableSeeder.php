<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::beginTransaction();
        $superAdmin = Role::firstOrCreate([
            'name' => 'super',
            'display_name' => 'Super Admin', // optional
            'description' => 'Highest Level', // optional
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'display_name' => 'Admin', // optional
            'description' => 'Second level', // optional
        ]);

        $regularUser = Role::firstOrCreate([
            'name' => 'user',
            'display_name' => 'User', // optional
            'description' => 'Third level', // optional
        ]);

        $superAdminPermissionArray = [];
        $adminPermissionArray = [];
        $userPermissionArray = [];

        $page = [
            'super-posts' => 'Super Posts',
            'super-users' => 'Super Users',
            'super-permissions' => 'Super Permissions',
        ];
        foreach ($page as $key => $value) {
            /**Create Permission to INDEX */
            $index = Permission::firstOrCreate(
                ['name' => $key.'-index', 'display_name' => $value.' Index']
            );

            /**Create Permission to CREATE */
            $create = Permission::firstOrCreate(
                ['name' => $key.'-create', 'display_name' => $value.' Create']
            );

            /**Create Permission to VIEW */
            $show = Permission::firstOrCreate(
                ['name' => $key.'-show', 'display_name' => $value.' Show']
            );

            /**Create Permission to EDIT */
            $edit = Permission::firstOrCreate(
                ['name' => $key.'-edit', 'display_name' => $value.' Edit']
            );

            /**Create Permission to delete */
            $destroy = Permission::firstOrCreate(
                ['name' => $key.'-delete', 'display_name' => $value.' Delete']
            );

            /**Attach Permission to Super Admin */
            $superAdminPermissionArray[] = $index->id;
            $superAdminPermissionArray[] = $create->id;
            $superAdminPermissionArray[] = $show->id;
            $superAdminPermissionArray[] = $edit->id;
            $superAdminPermissionArray[] = $destroy->id;
        }

        $page = [
            'admin-posts' => 'Admin Posts',
        ];
        foreach ($page as $key => $value) {
            /**Create Permission to INDEX */
            $index = Permission::firstOrCreate(
                ['name' => $key.'-index', 'display_name' => $value.' Index']
            );

            /**Create Permission to CREATE */
            $create = Permission::firstOrCreate(
                ['name' => $key.'-create', 'display_name' => $value.' Create']
            );

            /**Create Permission to VIEW */
            $show = Permission::firstOrCreate(
                ['name' => $key.'-show', 'display_name' => $value.' Show']
            );

            /**Create Permission to EDIT */
            $edit = Permission::firstOrCreate(
                ['name' => $key.'-edit', 'display_name' => $value.' Edit']
            );

            /**Create Permission to delete */
            $destroy = Permission::firstOrCreate(
                ['name' => $key.'-delete', 'display_name' => $value.' Delete']
            );

            /**Attach Permission to Admin */
            $adminPermissionArray[] = $index->id;
            $adminPermissionArray[] = $create->id;
            $adminPermissionArray[] = $show->id;
            $adminPermissionArray[] = $edit->id;
            $adminPermissionArray[] = $destroy->id;
        }

        $page = [
            'comments' => 'Comments',
            'likes' => 'Likes',
        ];
        foreach ($page as $key => $value) {
            /**Create Permission to INDEX */
            $index = Permission::firstOrCreate(
                ['name' => $key.'-index', 'display_name' => $value.' Index']
            );

            /**Create Permission to CREATE */
            $create = Permission::firstOrCreate(
                ['name' => $key.'-create', 'display_name' => $value.' Create']
            );

            /**Create Permission to VIEW */
            $show = Permission::firstOrCreate(
                ['name' => $key.'-show', 'display_name' => $value.' Show']
            );

            /**Create Permission to EDIT */
            $edit = Permission::firstOrCreate(
                ['name' => $key.'-edit', 'display_name' => $value.' Edit']
            );

            /**Create Permission to delete */
            $destroy = Permission::firstOrCreate(
                ['name' => $key.'-delete', 'display_name' => $value.' Delete']
            );

            /**Attach Permission to Super Admin */
            $superAdminPermissionArray[] = $index->id;
            $superAdminPermissionArray[] = $create->id;
            $superAdminPermissionArray[] = $show->id;
            $superAdminPermissionArray[] = $edit->id;
            $superAdminPermissionArray[] = $destroy->id;

            /**Attach Permission to Admin */
            $adminPermissionArray[] = $index->id;
            $adminPermissionArray[] = $create->id;
            $adminPermissionArray[] = $show->id;
            $adminPermissionArray[] = $edit->id;
            $adminPermissionArray[] = $destroy->id;

            /**Attach Permission to User */
            $userPermissionArray[] = $index->id;
            $userPermissionArray[] = $create->id;
            $userPermissionArray[] = $show->id;
            $userPermissionArray[] = $edit->id;
            $userPermissionArray[] = $destroy->id;
        }

        $page = [
            'notifications' => 'Notifications',
        ];
        foreach ($page as $key => $value) {
            /**Create Permission to INDEX */
            $index = Permission::firstOrCreate(
                ['name' => $key.'-index', 'display_name' => $value.' Index']
            );

            /**Create Permission to CREATE */
            $create = Permission::firstOrCreate(
                ['name' => $key.'-create', 'display_name' => $value.' Create']
            );

            /**Create Permission to VIEW */
            $show = Permission::firstOrCreate(
                ['name' => $key.'-show', 'display_name' => $value.' Show']
            );

            /**Create Permission to EDIT */
            $edit = Permission::firstOrCreate(
                ['name' => $key.'-edit', 'display_name' => $value.' Edit']
            );

            /**Create Permission to delete */
            $destroy = Permission::firstOrCreate(
                ['name' => $key.'-delete', 'display_name' => $value.' Delete']
            );

            /**Attach Permission to Super Admin */
            $superAdminPermissionArray[] = $index->id;
            $superAdminPermissionArray[] = $show->id;

            /**Attach Permission to Admin */
            $adminPermissionArray[] = $index->id;
            $adminPermissionArray[] = $show->id;

            /**Attach Permission to User */
            $userPermissionArray[] = $index->id;
            $userPermissionArray[] = $show->id;
        }

        $api = [
            'api-users' => 'API Users',
        ];
        foreach ($api as $key => $value) {
            /**Create Permission to INDEX */
            $index = Permission::firstOrCreate(
                ['name' => $key.'-index', 'display_name' => $value.' Index']
            );

            /**Create Permission to CREATE */
            $create = Permission::firstOrCreate(
                ['name' => $key.'-create', 'display_name' => $value.' Create']
            );

            /**Create Permission to SHOW */
            $show = Permission::firstOrCreate(
                ['name' => $key.'-show', 'display_name' => $value.' Show']
            );

            /**Create Permission to UPDATE */
            $update = Permission::firstOrCreate(
                ['name' => $key.'-update', 'display_name' => $value.' Update']
            );

            /**Create Permission to DESTROY */
            $destroy = Permission::firstOrCreate(
                ['name' => $key.'-destroy', 'display_name' => $value.' Destroy']
            );

            /**Attach Permission to Super Admin */
            $superAdminPermissionArray[] = $index->id;
            $superAdminPermissionArray[] = $create->id;
            $superAdminPermissionArray[] = $show->id;
            $superAdminPermissionArray[] = $update->id;
            $superAdminPermissionArray[] = $destroy->id;
        }

        $api = [
            'api-posts' => 'API Posts',
        ];
        foreach ($api as $key => $value) {
            /**Create Permission to INDEX */
            $index = Permission::firstOrCreate(
                ['name' => $key.'-index', 'display_name' => $value.' Index']
            );

            /**Create Permission to CREATE */
            $create = Permission::firstOrCreate(
                ['name' => $key.'-create', 'display_name' => $value.' Create']
            );

            /**Create Permission to SHOW */
            $show = Permission::firstOrCreate(
                ['name' => $key.'-show', 'display_name' => $value.' Show']
            );

            /**Create Permission to UPDATE */
            $update = Permission::firstOrCreate(
                ['name' => $key.'-update', 'display_name' => $value.' Update']
            );

            /**Create Permission to DESTROY */
            $destroy = Permission::firstOrCreate(
                ['name' => $key.'-destroy', 'display_name' => $value.' Destroy']
            );

            /**Attach Permission to Super Admin */
            $superAdminPermissionArray[] = $index->id;
            $superAdminPermissionArray[] = $create->id;
            $superAdminPermissionArray[] = $show->id;
            $superAdminPermissionArray[] = $update->id;
            $superAdminPermissionArray[] = $destroy->id;

            /**Attach Permission to Admin */
            $adminPermissionArray[] = $index->id;
            $adminPermissionArray[] = $create->id;
            $adminPermissionArray[] = $show->id;
            $adminPermissionArray[] = $update->id;
            $adminPermissionArray[] = $destroy->id;

            /**Attach Permission to User */
            $userPermissionArray[] = $index->id;
        }

        $api = [
            'api-comments' => 'API Comments',
            'api-likes' => 'API Likes',
        ];
        foreach ($api as $key => $value) {
            /**Create Permission to INDEX */
            $index = Permission::firstOrCreate(
                ['name' => $key.'-index', 'display_name' => $value.' Index']
            );

            /**Create Permission to CREATE */
            $create = Permission::firstOrCreate(
                ['name' => $key.'-create', 'display_name' => $value.' Create']
            );

            /**Create Permission to SHOW */
            $show = Permission::firstOrCreate(
                ['name' => $key.'-show', 'display_name' => $value.' Show']
            );

            /**Create Permission to UPDATE */
            $update = Permission::firstOrCreate(
                ['name' => $key.'-update', 'display_name' => $value.' Update']
            );

            /**Create Permission to DESTROY */
            $destroy = Permission::firstOrCreate(
                ['name' => $key.'-destroy', 'display_name' => $value.' Destroy']
            );

            /**Attach Permission to Super Admin */
            $superAdminPermissionArray[] = $index->id;
            $superAdminPermissionArray[] = $create->id;
            $superAdminPermissionArray[] = $show->id;
            $superAdminPermissionArray[] = $update->id;
            $superAdminPermissionArray[] = $destroy->id;

            /**Attach Permission to Admin */
            $adminPermissionArray[] = $index->id;
            $adminPermissionArray[] = $create->id;
            $adminPermissionArray[] = $show->id;
            $adminPermissionArray[] = $update->id;
            $adminPermissionArray[] = $destroy->id;

            /**Attach Permission to User */
            $userPermissionArray[] = $index->id;
            $userPermissionArray[] = $create->id;
            $userPermissionArray[] = $show->id;
            $userPermissionArray[] = $update->id;
            $userPermissionArray[] = $destroy->id;
        }

        $api = [
            'api-notifications' => 'API Notifications',
        ];
        foreach ($api as $key => $value) {
            /**Create Permission to INDEX */
            $index = Permission::firstOrCreate(
                ['name' => $key.'-index', 'display_name' => $value.' Index']
            );

            /**Create Permission to CREATE */
            $create = Permission::firstOrCreate(
                ['name' => $key.'-create', 'display_name' => $value.' Create']
            );

            /**Create Permission to SHOW */
            $show = Permission::firstOrCreate(
                ['name' => $key.'-show', 'display_name' => $value.' Show']
            );

            /**Create Permission to UPDATE */
            $update = Permission::firstOrCreate(
                ['name' => $key.'-update', 'display_name' => $value.' Update']
            );

            /**Create Permission to DESTROY */
            $destroy = Permission::firstOrCreate(
                ['name' => $key.'-destroy', 'display_name' => $value.' Destroy']
            );

            /**Attach Permission to Super Admin */
            $superAdminPermissionArray[] = $index->id;
            $superAdminPermissionArray[] = $show->id;

            /**Attach Permission to Admin */
            $adminPermissionArray[] = $index->id;
            $adminPermissionArray[] = $show->id;

            /**Attach Permission to User */
            $userPermissionArray[] = $index->id;
            $userPermissionArray[] = $show->id;
        }

        /**Attach Permission to Super Admin */
        $superAdmin->permissions()->sync($superAdminPermissionArray);

        /**Attach Permission to Admin */
        $admin->permissions()->sync($adminPermissionArray);

        /**Attach Permission to User */
        $regularUser->permissions()->sync($userPermissionArray);

        \DB::commit();
    }
}
