<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Status extends Model {

    use Notifiable;

    public $timestamps = false;
    protected $table = 'status';
    protected $primaryKey = "status_id";
    
    protected $fillable = [
        'name',
    ];
    protected $hidden = [
       'status_id','remember_token',
    ];

}
