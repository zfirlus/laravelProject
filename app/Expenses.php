<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Expenses extends Model {

    use Notifiable;

    public $timestamps = false;
    protected $table = 'expenses';
    protected $primaryKey = "expenses_id";
    protected $fillable = [
        'name', 'amount', 'user_id',
    ];
    protected $hidden = [
        'remember_token', 'expenses_id',
    ];

    public function scopeGetExpenses($query, $id) {
        return $query->where('user_id', '=', $id);
    }

    public function scopeGetExpense($query, $id) {
        return $query->where('expenses_id', '=', $id);
    }

    public function getAll(){
        return Expenses::all();
    }

    public function createExpense($data, $id) {
        $date = date_create('now')->format("Y-m-d H:i:s");
        $expense = new Expenses();
        $expense->name = $data->name;
        $expense->amount = 0;
        $expense->user_id = $id;
        $expense->created_at = $date;
        $expense->save();
    }

    public function editExpense($data) {
        $date = date_create('now')->format("Y-m-d H:i:s");
        Expenses::updateOrCreate(['expenses_id' => [$data->expenses_id]], ['name' => $data['name'],
            'update_at' => $date,]);
    }
    
    public function editAmount($id, $amount, $oldAmount){
        if(Expenses::updateOrCreate(['expenses_id' => [$id]], ['amount' => $oldAmount += $amount])){
            return true;
        }
        return false;
    }

    public function editAmountDec($id, $amount, $oldAmount){
        if(Expenses::updateOrCreate(['expenses_id' => [$id]], ['amount' => $oldAmount -= $amount])){
            return true;
        }
        return false;
    }
}
