<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {

    use HasRoles;
    use Notifiable;

    public $timestamps = false;
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'email', 'password', 'isadmin',
    ];
    protected $hidden = [
        'user_id', 'password', 'remember_token',
    ];

    public function roles() {
        return $this->belongsToMany(
                        config('laravel-permission.models.role'), config('laravel-permission.table_names.user_has_roles'), $this->primaryKey
        );
    }

    public function permissions() {
        return $this->belongsToMany(
                        config('laravel-permission.models.permission'), config('laravel-permission.table_names.user_has_permissions'), $this->primaryKey
        );
    }

    public function scopeGetUser($query, $id) {
        return $query->where('user_id', '=', $id);
    }

    public function getAll() {
        return User::all();
    }

    public function getUserAuth() {
        return Auth::user()->user_id;
    }

    public function createUser($u) {
        $user = new User();
        $user->email = $u->email;
        $user->password = bcrypt($u->password);
        $user->isAdmin = 0;
        $user->save();

        $user->assignRole('user');
    }

    public function editUserPassword($data) {
        $date = date_create('now')->format("Y-m-d H:i:s");
        User::updateOrCreate(['user_id' => [$data->user_id]], ['password' => bcrypt($data['password']),
            'update_at' => $date,]);
    }

    public function editUserEmail($data) {
        $date = date_create('now')->format("Y-m-d H:i:s");
        User::updateOrCreate(['user_id' => [$data->user_id]], ['email' => $data['email'],
            'update_at' => $date,]);
    }
    
    public function editUserRole($role, $userId){
        $date = date_create('now')->format("Y-m-d H:i:s");
        User::updateOrCreate(['user_id' => [$userId]], ['isadmin' => $role,
                'update_at' => $date,]);
    }

}
