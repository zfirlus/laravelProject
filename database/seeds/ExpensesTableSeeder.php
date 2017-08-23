<?php

use Illuminate\Database\Seeder;

class ExpensesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('expenses')->insert([
            'name' => 'opłaty za internet',
            'amount' => '50',
            'user_id' => '1',
        ]);
        DB::table('expenses')->insert([
            'name' => 'opłaty za prąd',
            'amount' => '100',
            'user_id' => '1',
        ]);
    }
}
