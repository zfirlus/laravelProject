<?php

use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status')->insert([
            'name' => 'niezatwierdzona',
        ]);
        DB::table('status')->insert([
            'name' => 'zatwierdzona',
        ]);
        DB::table('status')->insert([
            'name' => 'odrzucona',
        ]);
    }
}
