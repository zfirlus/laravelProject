<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');
        //admin
        Permission::create(['name' => 'add user']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'delete user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'add role']);
        Permission::create(['name' => 'accept payment']);
        //wspólne
        Permission::create(['name' => 'delete payment']);
        Permission::create(['name' => 'edit payment']);
        Permission::create(['name' => 'delete expense']);
        Permission::create(['name' => 'edit expense']);
        Permission::create(['name' => 'view expenses']);
        Permission::create(['name' => 'view payments']);
        //user
        Permission::create(['name' => 'add payment']);
        Permission::create(['name' => 'add expense']);
        
    }
}
