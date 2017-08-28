<?php

namespace App\Http\Controllers;

use App\Expenses;
use App\User;
use Illuminate\Http\Request;

class ExpensesController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id, $role) {

        if ($role === 0) {

            $expenses = Expenses::whereIn('user_id', [$id])->get();
            foreach ($expenses as $exp) {
                $payments = app('App\Http\Controllers\PaymentController')->getall($exp->expenses_id);

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
            $expenses = Expenses::all();
            return $expenses;
        }
    }

    public function editform($expenses_id) {
        $expenses = Expenses::whereIn('expenses_id', [$expenses_id])->get();
        $expense = $expenses[0];
        return View('user.editexpense', compact('expense'));
    }

    public function edit(Request $request) {

        $id = \Illuminate\Support\Facades\Auth::user()->user_id;
        $list = Expenses::whereIn('user_id', [$id])->get();
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

        $date = date_create('now')->format("Y-m-d H:i:s");

        Expenses::updateOrCreate(['expenses_id' => [$request->expenses_id]], ['name' => $request['name'],
            'update_at' => $date,]);

        $id = \Illuminate\Support\Facades\Auth::user()->user_id;
        $user = User::find($id);

        if ($user->isadmin === 1) {
            return redirect()->route('home');
        } else {
            return redirect()->route('home');
        }
    }

    public function createform() {
        return View('user.newexpense');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {

        $id = \Illuminate\Support\Facades\Auth::user()->user_id;
        $list = Expenses::whereIn('user_id', [$id])->get();
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

        $date = date_create('now')->format("Y-m-d H:i:s");

        $user = \Illuminate\Support\Facades\Auth::user()->user_id;

        Expenses::create([
            'name' => $request['name'],
            'amount' => '0',
            'user_id' => $user,
            'created_at' => $date,
        ]);
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function editamount($id, $amount) {

        $expense = Expenses::whereIn('expenses_id', [$id])->get();
        Expenses::updateOrCreate(['expenses_id' => [$id]], ['amount' => $expense[0]->amount += $amount]);

        return true;
    }

    public function editamountdec($id, $amount) {

        $expense = Expenses::whereIn('expenses_id', [$id])->get();
        Expenses::updateOrCreate(['expenses_id' => [$id]], ['amount' => $expense[0]->amount -= $amount]);

        return true;
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
