<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PaymentPolicy
 *
 * @author Zaneta
 */

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Payment;

class PaymentPolicy {

    use HandlesAuthorization;

    public function create($id) {
        return User::findOrFail($id)->hasPermissionTo('add payment');
    }

    public function update($id) {
        return User::findOrFail($id)->hasPermissionTo('edit payment');
    }

    public function delete($id) {
        return User::findOrFail($id)->hasPermissionTo('delete payment');
    }

    public function view($id) {
        return User::findOrFail($id)->hasPermissionTo('view payments');
    }
    
    public function accept($id) {
        return User::findOrFail($id)->hasPermissionTo('accept payment');
    }
}