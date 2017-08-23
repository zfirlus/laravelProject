<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Payment extends Model
{
    use Notifiable;

    public $timestamps = false;
    protected $table = 'payment';

    protected $fillable = [
        'status_id', 'expenses_id', 'created_at', 'client', 'amount',
    ];

    protected $hidden = [
        'payment_id', 'remember_token',
    ];
}
