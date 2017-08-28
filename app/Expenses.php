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

}
