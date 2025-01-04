<?php

namespace Database\Seeders;


use App\Models\Users\User;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::create(['name' => 'insert energySheet']);
        Permission::create(['name' => 'delete site']);
        Permission::create(['name' => 'update site']);
        Permission::create(['name' => 'create site']);
        Permission::create(['name' => 'add nur']);
        Permission::create(['name' => 'insert sites_sheet']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'delete user']);


        $role1 = Role::create(['name' => 'super-admin']);
        $role1->givePermissionTo('insert energySheet');
        $role1->givePermissionTo('delete site');
        $role1->givePermissionTo('update site');
        $role1->givePermissionTo('create site');
        $role1->givePermissionTo('add nur');
        $role1->givePermissionTo('insert sites_sheet');
        $role1->givePermissionTo('create user');
        $role1->givePermissionTo('update user');
        $role1->givePermissionTo('delete user');

        $role2 = Role::create(['name' => 'admin']);
        $role2->givePermissionTo('insert energySheet');
        $role2->givePermissionTo('delete site');
        $role2->givePermissionTo('update site');
        $role2->givePermissionTo('create site');
        $role2->givePermissionTo('add nur');
        $role2->givePermissionTo('insert sites_sheet');


        $role3 = Role::create(['name' => 'user']);

        $user1 = User::create([
            "name" => "amr morsy",
            "email" => "morsy.mamr@gmail.com",
            "password" => bcrypt("Mobinil@2020"),

        ]);

        $user1->assignRole($role1);

        $user2 = User::create([
            "name" => "heba kawkab",
            "email" => "h.kawkab@gmail.com",
            "password" => bcrypt("Mobinil@2020"),

        ]);
        $user2->assignRole($role3);
    }
}
