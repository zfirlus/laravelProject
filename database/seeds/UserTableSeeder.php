<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('user')->insert([
            'email' => 'katarzyna@gmail.com',
            'password' => 'Katarzyna10',
            'isadmin' => '0',
        ]);
        DB::table('user')->insert([
            'email' => 'anna@gmail.com',
            'password' => 'Anna20',
            'isadmin' => '1',
        ]);
    }

}
