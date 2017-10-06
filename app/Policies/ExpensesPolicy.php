<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExpensesPolicy
 *
 * @author Zaneta
 */

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Expenses;

class ExpensesPolicy {

    use HandlesAuthorization;

    public function create($id) {
        return User::findOrFail($id)->hasPermissionTo('add expense');
    }

    public function update($id) {
        return User::findOrFail($id)->hasPermissionTo('edit expense');
    }

    public function delete($id) {
        return User::findOrFail($id)->hasPermissionTo('delete expense');
    }

    public function view($id) {
        return  User::findOrFail($id)->hasPermissionTo('view expenses');
    }
}
