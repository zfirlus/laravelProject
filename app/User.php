<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable {

    use Notifiable;

    public $timestamps = false;
    protected $table = 'users';
    protected $primaryKey = "user_id";
    
    protected $fillable = [
       'email', 'password', 'isadmin',
    ];

    protected $hidden = [
       'user_id','password', 'remember_token',
    ];

}
