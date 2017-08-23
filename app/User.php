<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Model {

    use Notifiable;

    public $timestamps = false;
    protected $table = 'user';

    protected $fillable = [
        'user_id', 'email', 'password', 'isadmin',
    ];

    protected $hidden = [
        'user_id', 'password', 'remember_token',
    ];

}
