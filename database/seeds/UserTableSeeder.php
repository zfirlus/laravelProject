<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('users')->insert([
            'email' => 'katarzyna@gmail.com',
            'password' => bcrypt('katarzyna'),
            'isadmin' => '0',
        ]);
        DB::table('users')->insert([
            'email' => 'anna@gmail.com',
            'password' => bcrypt('anna20'),
            'isadmin' => '1',
        ]);
    }

}
