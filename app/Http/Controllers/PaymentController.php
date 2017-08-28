<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Status;
use App\Expenses;
use App\User;
use Illuminate\Http\Request;

class PaymentController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
        $id = \Illuminate\Support\Facades\Auth::user()->user_id;
        $expenses = Expenses::whereIn('user_id', [$id])->get();
        $expense_id = session()->get('expense');
        
        return View('user.newpayment', compact('expenses', 'expense_id'));
    }

    public function getall($id) {
        $payments = Payment::whereIn('expenses_id', [$id])->get();
        return $payments;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        
        $messages = [
            'client.required' => 'To pole nie może być puste!',
            'client.min' => 'Odbiorca musi zawierać conajmniej 3 znaki!',
            'client.max' => 'Odbiorca nie może przekraczać 30 znaków',
            'amount.required' => 'To pole nie może być puste!',
            'amount.min' => 'Kwota musi być równa conajmniej 1!',
            'amount.max' => 'Wprowadzona kwota jest zbyt duża!',
            'numeric' => 'Niepoprawna kwota!'
        ];
        
        $this->validate($request, [
            'client' => 'required|min:3|string|max:30',
            'amount' => 'required|min:1|numeric|max:999999,99',
                ], $messages);
        
        $date = date_create('now')->format("Y-m-d H:i:s");
        
        Payment::create([
            'status_id' => '1',
            'expenses_id' => Value($request['expenses']),
            'created_at' => $date,
            'client' => $request['client'],
            'amount' => $request['amount'],
        ]);
        
        $id = session()->get('expense');
        
        if (app('App\Http\Controllers\ExpensesController')->editamount(Value($request['expenses']), $request['amount'])) {
            return $this::payments($id);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $payment = Payment::whereIn('expenses_id', [$request->expenses_id])->get();
        $status = Status::all();

        foreach ($payment as $p) {
            foreach ($status as $s) {
                if ($p->status_id === $s->status_id) {
                    $p->status = $s->name;
                }
            }
        }
        session()->put('expense', $request->expenses_id);
        $page = $request->get('page', 1);
        $perPage = 4;
        
        return view('user.payment', [
            'payment' => $payment->forPage($page, $perPage),
            'pagination' => \BootstrapComponents::pagination($payment, $page, $perPage, '', ['arrows' => true]),
        ]);
    }

    public function payments($id) {
        $payment = Payment::whereIn('expenses_id', [$id])->get();
        $status = Status::all();

        foreach ($payment as $p) {
            foreach ($status as $s) {
                if ($p->status_id === $s->status_id) {
                    $p->status = $s->name;
                }
            }
        }
        $idd = \Illuminate\Support\Facades\Auth::user()->user_id;
        $user = User::whereIn('user_id', [$idd])->get();
        
        if ($user[0]->isadmin === 0) {
            return redirect()->route('payment',$id);
        } else {
            return redirect()->route('adminpayment',$id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request) {
        $payment = Payment::whereIn('expenses_id', [$request->expenses_id])->get();
        $status = Status::all();

        foreach ($payment as $p) {
            foreach ($status as $s) {
                if ($p->status_id === $s->status_id) {
                    $p->status = $s->name;
                }
            }
        }
        session()->put('expense', $request->expenses_id);
        
        $page = $request->get('page', 1);
        $perPage = 4;
        
        return view('admin.payment', [
                'payment' => $payment->forPage($page, $perPage),
                'pagination' => \BootstrapComponents::pagination($payment, $page, $perPage, '', ['arrows' => true]),
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request) {
        
        $messages = [
            'client.required' => 'To pole nie może być puste!',
            'client.min' => 'Odbiorca musi zawierać conajmniej 3 znaki!',
            'client.max' => 'Odbiorca nie może przekraczać 30 znaków',
            'amount.required' => 'To pole nie może być puste!',
            'amount.min' => 'Kwota musi być równa conajmniej 1!',
            'amount.max' => 'Wprowadzona kwota jest zbyt duża!',
            'numeric' => 'Niepoprawna kwota!'
        ];
        
        $this->validate($request, [
            'client' => 'required|min:3|string|max:30',
            'amount' => 'required|min:1|numeric|max:999999.99',
                ], $messages);
        
        $date = date_create('now')->format("Y-m-d H:i:s");
        
        $payment = Payment::whereIn('payment_id', [$request->payment_id])->get();
        $amount = $payment[0]->amount;
        
        Payment::updateOrCreate(['payment_id' => [$request->payment_id]], ['expenses_id' => Value($request['expenses']),
            'update_at' => $date, 'client' => $request['client'], 'amount' => $request['amount']]);

        $id = session()->get('expense');
        
        if ($amount > $request->amount) {
            
            $value = $amount - $request->amount;
            if (Value($request['expenses']) !== $payment[0]->expenses_id) {
                $oldexpense = app('App\Http\Controllers\ExpensesController')->editamountdec($payment[0]->expenses_id, $amount);
                $newexpense = app('App\Http\Controllers\ExpensesController')->editamount(Value($request['expenses']), $request['amount']);
            } else {
                $oldexpense = app('App\Http\Controllers\ExpensesController')->editamountdec($id, $value);
            }
            return $this::payments($id);
        }
        
        if ($amount < $request->amount) {
            $value = $request->amount - $amount;
            
            if (Value($request['expenses']) !== $payment[0]->expenses_id) {
                
                $oldexpense = app('App\Http\Controllers\ExpensesController')->editamountdec($payment[0]->expenses_id, $amount);
                $newexpense = app('App\Http\Controllers\ExpensesController')->editamount(Value($request['expenses']), $request['amount']);
            } else {
                $oldexpense = app('App\Http\Controllers\ExpensesController')->editamount($id, $value);
            }
            return $this::payments($id);
        }
        
        if ($amount == $request->amount) {
            if (Value($request['expenses']) !== $payment[0]->expenses_id) {
                
                $oldexpense = app('App\Http\Controllers\ExpensesController')->editamountdec($payment[0]->expenses_id, $amount);
                $newexpense = app('App\Http\Controllers\ExpensesController')->editamount(Value($request['expenses']), $request['amount']);
            }
            return $this::payments($id);
        }
    }

    public function adminedit(Request $request) {
        
        $messages = [
            'client.required' => 'To pole nie może być puste!',
            'client.min' => 'Odbiorca musi zawierać conajmniej 3 znaki!',
            'client.max' => 'Odbiorca nie może przekraczać 30 znaków',
            'amount.required' => 'To pole nie może być puste!',
            'amount.min' => 'Kwota musi być równa conajmniej 1!',
            'numeric' => 'Niepoprawna kwota!'
        ];
        
        $this->validate($request, [
            'client' => 'required|min:3|string|max:30',
            'amount' => 'required|min:1|numeric|max:99999999.99',
                ], $messages);
        
        $date = date_create('now')->format("Y-m-d H:i:s");
        $payment = Payment::whereIn('payment_id', [$request->payment_id])->get();
        $amount = $payment[0]->amount;
        
        Payment::updateOrCreate(['payment_id' => [$request->payment_id]], ['status_id' => Value($request['status']), 'expenses_id' => Value($request['expenses']),
            'update_at' => $date, 'client' => $request['client'], 'amount' => $request['amount']]);

        $id = session()->get('expense');
        
        if ($amount > $request->amount) {
            $value = $amount - $request->amount;
            
            if (Value($request['expenses']) !== $payment[0]->expenses_id) {
                
                $oldexpense = app('App\Http\Controllers\ExpensesController')->editamountdec($payment[0]->expenses_id, $amount);
                $newexpense = app('App\Http\Controllers\ExpensesController')->editamount(Value($request['expenses']), $request['amount']);
            } 
            else {
                $oldexpense = app('App\Http\Controllers\ExpensesController')->editamountdec($id, $value);
            }
            return $this::payments($id);
        }
        
        if ($amount < $request->amount) {
            
            $value = $request->amount - $amount;
            if (Value($request['expenses']) !== $payment[0]->expenses_id) {
                
                $oldexpense = app('App\Http\Controllers\ExpensesController')->editamountdec($payment[0]->expenses_id, $amount);
                $newexpense = app('App\Http\Controllers\ExpensesController')->editamount(Value($request['expenses']), $request['amount']);
            } 
            else {
                $oldexpense = app('App\Http\Controllers\ExpensesController')->editamount($id, $value);
            }
            return $this::payments($id);
        }
        
        if ($amount == $request->amount) {
            
            if (Value($request['expenses']) !== $payment[0]->expenses_id) {
                
                $oldexpense = app('App\Http\Controllers\ExpensesController')->editamountdec($payment[0]->expenses_id, $amount);
                $newexpense = app('App\Http\Controllers\ExpensesController')->editamount(Value($request['expenses']), $request['amount']);
            }
            return $this::payments($id);
        }
    }

    public function editform($payment_id) {
        
        $id = \Illuminate\Support\Facades\Auth::user()->user_id;
        $expenses = Expenses::whereIn('user_id', [$id])->get();
        $payments = Payment::whereIn('payment_id', [$payment_id])->get();
        $payment = $payments[0];
        
        return View('user.editpayment', compact('expenses', 'payment'));
    }

    public function admineditform($payment_id) {
        
        $payments = Payment::whereIn('payment_id', [$payment_id])->get();
        $payment = $payments[0];
        $expense = Expenses::whereIn('expenses_id', [$payment->expenses_id])->get();
        $id = $expense[0]->user_id;
        $expenses = Expenses::whereIn('user_id', [$id])->get();
        $status = Status::all();
        
        return View('admin.editpayment', compact('expenses', 'payment', 'status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment) {
        //
    }

}
