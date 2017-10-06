<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

//use Spatie\Permission\Traits\HasRoles;


class Payment extends Model {

    use Notifiable;

    // use HasRoles;
    //protected $guard_name = 'web';

    public $timestamps = false;
    protected $table = 'payment';
    protected $primaryKey = "payment_id";
    protected $fillable = [
        'status_id', 'expenses_id', 'created_at', 'client', 'amount',
    ];
    protected $hidden = [
        'payment_id', 'remember_token',
    ];

    public function scopeGetPayments($query, $id) {
        return $query->where('expenses_id', '=', $id);
    }

    public function scopeGetPayment($query, $id){
        return $query->where('payment_id', '=', $id);
    }
   
    public function createPayment($data) {
        $date = date_create('now')->format("Y-m-d H:i:s");
        
        $payment = new Payment();
        $payment->status_id = 1;
        $payment->expenses_id = Value($data->expenses);
        $payment->client = $data->client;
        $payment->amount = $data->amount;
        $payment->created_at = $date;
        $payment->save();
    }

    public function editPayment($data) {
        $date = date_create('now')->format("Y-m-d H:i:s");
        Payment::updateOrCreate(['payment_id' => [$data->payment_id]], ['expenses_id' => Value($data['expenses']),
            'update_at' => $date, 'client' => $data['client'], 'amount' => $data['amount']]);
    }
    
    public function editPaymentAdmin($data){
        $date = date_create('now')->format("Y-m-d H:i:s");
        Payment::updateOrCreate(['payment_id' => [$data->payment_id]], ['status_id' => Value($data['status']), 'expenses_id' => Value($data['expenses']),
                'update_at' => $date, 'client' => $data['client'], 'amount' => $data['amount']]);
    }

}
