<?php

namespace App\Http\Controllers;

use App\Expenses;
use App\User;
use App\Payment;
use App\Policies\ExpensesPolicy;
use Illuminate\Http\Request;

class ExpensesController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->userModel = new User();
        $this->expensesModel = new Expenses();
        $this->paymentModel = new Payment();
        $this->expensesPolicy = new ExpensesPolicy();
    }
    
    public function index($id, $role) {

        if ($role === 0) {

            $expenses = $this->expensesModel->getExpenses($id)->get();
            foreach ($expenses as $exp) {
                
                $payments = $this->paymentModel->getPayments($exp->expenses_id)->get();
                if (Count($payments) === 0) {
                    return $expenses;
                } else {
                    $i = 0;

                    foreach ($payments as $pay) {

                        if ($pay->status_id === 2) {
                            $i++;
                        }
                    }
                    if ($i > 0) {
                        $exp->status = 'block';
                    }
                }
            }
            return $expenses;
        }
        if ($role === 1) {
            $expenses = $this->expensesModel->getAll();
            return $expenses;
        }
    }

    public function editForm($expenses_id) {
        
        $expense = $this->expensesModel->getExpense($expenses_id)->get()[0];
        
        return View('user.editexpense', compact('expense'));
    }

    public function edit(Request $request) {
        
        if ($this->expensesPolicy->update($this->userModel->getUserAuth())) {
            $id = $this->userModel->getUserAuth();
            
            $list = $this->expensesModel->getExpenses($id)->get();
            $i = 0;

            foreach ($list as $l) {
                if ($l->name === $request['name']) {
                    $i++;
                }
            }
            $messages = [
                'name.max' => 'Nazwa nie może przekraczać 30 znaków!',
                'name.min' => 'Nazwa musi mieć przynajmniej 3 znaki!',
                'name.required' => 'Pole nie może być puste!',
                'uniquename' => 'Posiadasz już wydatki o takiej nazwie!',
            ];

            $this->validate($request, [
                'name' => 'min:3|required|string|max:30|uniquename:' . $i,
                    ], $messages);
            $this->expensesModel->editExpense($request);
            
            return redirect()->route('home');
        }
        
        session()->flash('message', 'Nie posiadasz uprawnień, aby edytować wydatek!');
        return redirect()->route('home');
    }

    public function createForm() {
        return View('user.newexpense');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if ($this->expensesPolicy->create($this->userModel->getUserAuth())) {
            $id = $this->userModel->getUserAuth();
            $list = $this->expensesModel->getExpenses($id)->get();
            $i = 0;

            foreach ($list as $l) {
                if ($l->name === $request['name']) {
                    $i++;
                }
            }

            $messages = [
                'name.max' => 'Nazwa nie może przekraczać 30 znaków!',
                'name.min' => 'Nazwa musi mieć przynajmniej 3 znaki!',
                'name.required' => 'Pole nie może być puste!',
                'uniquename' => 'Posiadasz już wydatki o takiej nazwie!',
            ];

            $this->validate($request, [
                'name' => 'min:3|required|string|max:30|uniquename:' . $i,
                    ], $messages);

            $this->expensesModel->createExpense($request, $id);
            
            return redirect()->route('home');
        }
        session()->flash('message', 'Nie posiadasz uprawnień, aby dodać wydatek!');
        return redirect()->route('home');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function show(Expenses $expenses) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expenses $expenses) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expenses $expenses) {
        //
    }

}
