<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class UserTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        $user = User::create([
            'email' => 'katarzyna@gmail.com',
            'password' => bcrypt('katarzyna'),
            'isadmin' => '0',
        ]);
        
        $user->assignRole('user');
        
        $user2 =User::create([
            'email' => 'anna@gmail.com',
            'password' => bcrypt('anna20'),
            'isadmin' => '1',
        ]);
        $user2->assignRole('admin');
    }

}
