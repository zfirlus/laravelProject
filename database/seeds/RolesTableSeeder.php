<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'user']);
        
        $role->givePermissionTo('add payment');
        $role->givePermissionTo('add expense');
        $role->givePermissionTo('delete payment');
        $role->givePermissionTo('edit payment');
        $role->givePermissionTo('delete expense');
        $role->givePermissionTo('edit expense');
        $role->givePermissionTo('view expenses');
        $role->givePermissionTo('view payments');
        
        $role2 = Role::create(['name' => 'admin']);
        
        $role2->givePermissionTo('add user');
        $role2->givePermissionTo('view users');
        $role2->givePermissionTo('delete user');
        $role2->givePermissionTo('edit user');
        $role2->givePermissionTo('add role');
        $role2->givePermissionTo('accept payment');
        $role2->givePermissionTo('delete payment');
        $role2->givePermissionTo('edit payment');
        $role2->givePermissionTo('delete expense');
        $role2->givePermissionTo('edit expense');
        $role2->givePermissionTo('view expenses');
        $role2->givePermissionTo('view payments');
    }
}
