<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;
use App\Expenses;
use App\User;
use App\Policies\PaymentPolicy;
use App\Policies\ExpensesPolicy;
use App\Policies\UserPolicy;
use App\Http\Controllers\Controller;

class AjaxController extends Controller {

    public function __construct() {
        $this->userModel = new User();
        $this->expensesModel = new Expenses();
        $this->paymentModel = new Payment();
        $this->paymentPolicy = new PaymentPolicy();
        $this->expensesPolicy = new ExpensesPolicy();
        $this->userPolicy = new UserPolicy();
    }

    public function deletePayment(Request $r) {
        if ($this->paymentPolicy->delete($this->userModel->getUserAuth())) {
            $data = $r->all();
            $id = $data['data'];
            for ($i = 0; $i < Count($id); $i++) {
                $expenseId = session()->get('expense');
                $amount = $this->paymentModel->getPayment($id[$i])->get()[0]->amount;
                if ($this->expensesModel->editAmountDec($expenseId, $amount, $this->expensesModel->getExpense($expenseId)->get()[0]->amount)) {
                    $payment = $this->paymentModel->getPayment($id[$i])->get()[0]->delete();
                }
            }
            return response(['success']);
        }
        session()->flash('message', 'Nie posiadasz uprawnień, aby usunąć płatność!');
        return response(['error']);
    }

    public function deleteExpense(Request $r) {
        if ($this->expensesPolicy->delete($this->userModel->getUserAuth())) {
            $data = $r->all();
            $id = $data['data'];
            for ($i = 0; $i < Count($id); $i++) {
                $payments = $this->paymentModel->getPayments($id[$i])->get();
                for ($j = 0; $j < Count($payments); $j++) {
                    $payments[$j]->delete();
                }
                $this->expensesModel->getExpense($id[$i])->get()[0]->delete();
            }
            return response(['success']);
        }
        session()->flash('message', 'Nie posiadasz uprawnień, aby usunąć wydatek!');
        return response(['error']);
    }

    public function deleteUser(Request $r) {
        if ($this->userPolicy->delete($this->userModel->getUserAuth())) {
            $data = $r->all();
            $id = $data['data'];

            for ($i = 0; $i < Count($id); $i++) {
                $expenses = $this->expensesModel->getExpenses($id[$i])->get();

                for ($e = 0; $e < Count($expenses); $e++) {
                    $payments = $this->paymentModel->getPayments($expenses[$e]->expenses_id)->get();
                    for ($j = 0; $j < Count($payments); $j++) {
                        $payments[$j]->delete();
                    }
                    $expenses[$e]->delete();
                }
                $this->userModel->getUser($id[$i])->get()[0]->removeRole('user');
                $this->userModel->getUser($id[$i])->get()[0]->removeRole('admin');
                $this->userModel->getUser($id[$i])->get()[0]->delete();
            }
            return response(['success']);
        }
        session()->flash('message', 'Nie posiadasz uprawnień, aby usunąć użytkownika!');
        return response(['error']);
    }

    public function saveRole(Request $r) {
        if ($this->userPolicy->role($this->userModel->getUserAuth())) {
            $data = $r->all();
            $role = $data['role'];
            $user = $data['user'];
            $this->userModel->editUserRole($role, $user);
            return response(['success']);
        }
        session()->flash('message', 'Nie posiadasz uprawnień, aby dodać rolę użytkownikowi!');
        return response(['error']);
    }

}
