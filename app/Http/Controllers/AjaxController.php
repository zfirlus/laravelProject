<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;
use App\Expenses;
use App\User;
use App\Http\Controllers\Controller;

class AjaxController extends Controller {

    public function deletepayment(Request $r) {
        $data = $r->all();
        $id = $data['data'];
        for ($i = 0; $i < Count($id); $i++) {
            
            $idexpenses = session()->get('expense');
            $pay = Payment::whereIn('payment_id', [$id[$i]])->get();
            $amount = $pay[0]->amount;

            if (app('App\Http\Controllers\ExpensesController')->editamountdec($idexpenses, $amount)) {
                $payment = Payment::whereIn('payment_id', [$id[$i]])->delete();
            }
        }
        return response(['success']);
    }

    public function deleteexpense(Request $r) {
        
        $data = $r->all();
        $id = $data['data'];
        
        for ($i = 0; $i < Count($id); $i++) {
            
            $payments = Payment::whereIn('expenses_id', [$id[$i]])->delete();
            $expense = Expenses::whereIn('expenses_id', [$id[$i]])->delete();
        }
        return response(['success']);
    }

    public function deleteuser(Request $r) {
        
        $data = $r->all();
        $id = $data['data'];
        
        for ($i = 0; $i < Count($id); $i++) {
            $expenses = Expenses::whereIn('user_id', [$id[$i]])->get();
            
            foreach ($expenses as $ex) {
                
                $payments = Payment::whereIn('expenses_id', [$ex->expenses_id])->delete();
                $ex->delete();
            }
            $user = User::whereIn('user_id', [$id[$i]])->delete();
        }
        return response(['success']);
    }

    public function saverole(Request $r) {
        
        $data = $r->all();
        $role = $data['role'];
        $user = $data['user'];
        
        $date = date_create('now')->format("Y-m-d H:i:s");
        User::updateOrCreate(['user_id' => [$user]], ['isadmin' => $role,
            'update_at' => $date,]);
        return response(['success']);
    }

}
