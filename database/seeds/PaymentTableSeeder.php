<?php

use Illuminate\Database\Seeder;

class PaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment')->insert([
            'status_id' => '1',
            'expenses_id' => '1',
            'amount' => '50',
            'client' => 'internet',
            'created_at' => '2017-09-08 12:16:20',
        ]);
        DB::table('payment')->insert([
            'status_id' => '1',
            'expenses_id' => '2',
            'amount' => '100',
            'client' => 'prąd',
            'created_at' => '2017-09-06 15:08:13',
        ]);
        DB::table('payment')->insert([
            'status_id' => '2',
            'expenses_id' => '2',
            'amount' => '100',
            'client' => 'prąd',
            'created_at' => '2017-08-12 11:14:07',
        ]);
    }
}
