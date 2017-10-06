<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use App\User;

/**
 * Description of UserPolicies
 *
 * @author Zaneta
 */
class UserPolicy {

    use HandlesAuthorization;

    public function create($id) {
        return User::findOrFail($id)->hasPermissionTo('add user');
    }
    public function update($id) {
        return User::findOrFail($id)->hasPermissionTo('edit user');
    }

    public function delete($id) {
        return User::findOrFail($id)->hasPermissionTo('delete user');
    }
    
    public function view($id) {
        return User::findOrFail($id)->hasPermissionTo('view users');
    }

    public function role($id) {
        return User::findOrFail($id)->hasPermissionTo('add role');
    }
}
