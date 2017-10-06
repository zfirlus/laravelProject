<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Status;
use App\Expenses;
use App\User;
use App\Policies\PaymentPolicy;
use Illuminate\Http\Request;

class PaymentController extends Controller {

    public function __construct() {
        $this->paymentModel = new Payment();
        $this->userModel = new User();
        $this->expensesModel = new Expenses();
        $this->statusModel = new Status();
        $this->paymentPolicy = new PaymentPolicy();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $id = $this->userModel->getUserAuth();
        $expenses = $this->expensesModel->getExpenses($id)->get();
        $expense_id = session()->get('expense');

        return View('user.newpayment', compact('expenses', 'expense_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if ($this->paymentPolicy->create($this->userModel->getUserAuth())) {
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

            $this->paymentModel->createPayment($request);

            $id = session()->get('expense');
            $oldAmount = $this->expensesModel->getExpense(Value($request['expenses']))->get()[0]->amount;

            if ($this->expensesModel->editAmount(Value($request['expenses']), $request['amount'], $oldAmount)) {
                return $this::payments($id);
            }
        }
        session()->flash('message', 'Nie posiadasz uprawnień, aby dodać nową płatność!');
        return $this::payments(session()->get('expense'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if ($this->paymentPolicy->view($this->userModel->getUserAuth())) {
            $payments = $this->paymentModel->getPayments($request->expenses_id)->get();
            $status = $this->statusModel->getAll();

            foreach ($payments as $p) {
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
                'payment' => $payments->forPage($page, $perPage),
                'pagination' => \BootstrapComponents::pagination($payments, $page, $perPage, '', ['arrows' => true]),
            ]);
        }

        session()->flash('message', 'Nie posiadasz uprawnień, aby wyświetlić listę płatności!');
        return view('user.payment', [
            'pagination' => \BootstrapComponents::pagination(null, 0, 4, '', ['arrows' => true]),
        ]);
    }

    public function payments($id) {
        $payments = $this->paymentModel->getPayments($id)->get();
        $status = $this->statusModel->getAll();

        foreach ($payments as $p) {
            foreach ($status as $s) {
                if ($p->status_id === $s->status_id) {
                    $p->status = $s->name;
                }
            }
        }

        $user = $this->userModel->getUser($this->userModel->getUserAuth())->get()[0];

        if ($user->isAdmin === 0) {
            return redirect()->route('payment', $id);
        } else {
            return redirect()->route('adminPayment', $id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request) {
        if ($this->paymentPolicy->view($this->userModel->getUserAuth())) {
            $payments = $this->paymentModel->getPayments($request->expenses_id)->get();
            $status = $this->statusModel->getAll();

            foreach ($payments as $p) {
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
                'payment' => $payments->forPage($page, $perPage),
                'pagination' => \BootstrapComponents::pagination($payments, $page, $perPage, '', ['arrows' => true]),
            ]);
        }
        session()->flash('message', 'Nie posiadasz uprawnień, aby wyświetlić listę płatności!');
        return view('admin.payment', [
            'pagination' => \BootstrapComponents::pagination(null, 0, 4, '', ['arrows' => true]),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request) {
        if ($this->paymentPolicy->update($this->userModel->getUserAuth())) {
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

            $payment = $this->paymentModel->getPayment($request->payment_id)->get()[0];
            $amount = $payment->amount;

            $this->paymentModel->editPayment($request);
            $id = session()->get('expense');

            if ($amount > $request->amount) {
                $value = $amount - $request->amount;
                if (Value($request['expenses']) !== $payment->expenses_id) {
                    $oldExpense = $this->expensesModel->editAmountDec($payment->expenses_id, $amount, $this->expensesModel->getExpense($payment->expenses_id)->get()[0]->amount);
                    $newExpense = $this->expensesModel->editAmount(Value($request['expenses']), $request['amount'], $this->expensesModel->getExpense(Value($request['expenses']))->get()[0]->amount);
                } else {
                    $oldExpense = $this->expensesModel->editAmountDec($payment->expenses_id, $value, $this->expensesModel->getExpense($payment->expenses_id)->get()[0]->amount);
                }
                return $this::payments(session()->get('expense'));
            }

            if ($amount < $request->amount) {
                $value = $request->amount - $amount;
                if (Value($request['expenses']) !== $payment->expenses_id) {
                    $oldExpense = $this->expensesModel->editAmountDec($payment->expenses_id, $amount, $this->expensesModel->getExpense($payment->expenses_id)->get()[0]->amount);
                    $newExpense = $this->expensesModel->editAmount(Value($request['expenses']), $request['amount'], $this->expensesModel->getExpense(Value($request['expenses']))->get()[0]->amount);
                } else {
                    $oldExpense = $this->expensesModel->editAmount($payment->expenses_id, $value, $this->expensesModel->getExpense($payment->expenses_id)->get()[0]->amount);
                }
                return $this::payments($id);
            }

            if ($amount == $request->amount && Value($request['expenses']) !== $payment->expenses_id) {

                $oldExpense = $this->expensesModel->editAmountDec($payment->expenses_id, $amount, $this->expensesModel->getExpense($payment->expenses_id)->get()[0]->amount);
                $newExpense = $this->expensesModel->editAmount(Value($request['expenses']), $request['amount'], $this->expensesModel->getExpense(Value($request['expenses']))->get()[0]->amount);

                return $this::payments($id);
            }
        }
        session()->flash('message', 'Nie posiadasz uprawnień, aby edytować płatność!');
        return $this::payments(session()->get('expense'));
    }

    public function adminEdit(Request $request) {
        if ($this->paymentPolicy->update($this->userModel->getUserAuth())) {
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

            $payment = $this->paymentModel->getPayment($request->payment_id)->get()[0];
            $amount = $payment->amount;

            $this->paymentModel->editPaymentAdmin($request);
            $id = session()->get('expense');

            if ($amount > $request->amount) {
                $value = $amount - $request->amount;
                if (Value($request['expenses']) !== $payment->expenses_id) {
                    $oldExpense = $this->expensesModel->editAmountDec($payment->expenses_id, $amount, $this->expensesModel->getExpense($payment->expenses_id)->get()[0]->amount);
                    $newExpense = $this->expensesModel->editAmount(Value($request['expenses']), $request['amount'], $this->expensesModel->getExpense(Value($request['expenses']))->get()[0]->amount);
                } else {
                    $oldExpense = $this->expensesModel->editAmountDec($payment->expenses_id, $value, $this->expensesModel->getExpense($payment->expenses_id)->get()[0]->amount);
                }
                return $this::payments(session()->get('expense'));
            }

            if ($amount < $request->amount) {
                $value = $request->amount - $amount;
                if (Value($request['expenses']) !== $payment->expenses_id) {
                    $oldExpense = $this->expensesModel->editAmountDec($payment->expenses_id, $amount, $this->expensesModel->getExpense($payment->expenses_id)->get()[0]->amount);
                    $newExpense = $this->expensesModel->editAmount(Value($request['expenses']), $request['amount'], $this->expensesModel->getExpense(Value($request['expenses']))->get()[0]->amount);
                } else {
                    $oldExpense = $this->expensesModel->editAmount($payment->expenses_id, $value, $this->expensesModel->getExpense($payment->expenses_id)->get()[0]->amount);
                }
                return $this::payments($id);
            }

            if ($amount == $request->amount && Value($request['expenses']) !== $payment->expenses_id) {

                $oldExpense = $this->expensesModel->editAmountDec($payment->expenses_id, $amount, $this->expensesModel->getExpense($payment->expenses_id)->get()[0]->amount);
                $newExpense = $this->expensesModel->editAmount(Value($request['expenses']), $request['amount'], $this->expensesModel->getExpense(Value($request['expenses']))->get()[0]->amount);

                return $this::payments($id);
            }
        }
        session()->flash('message', 'Nie posiadasz uprawnień, aby edytować płatność!');
    }

    public function editForm($payment_id) {

        $expenses = $this->expensesModel->getExpenses($this->userModel->getUserAuth())->get();
        $payment = $this->paymentModel->getPayment($payment_id)->get()[0];
        return View('user.editpayment', compact('expenses', 'payment'));
    }

    public function adminEditForm($payment_id) {

        $payment = $this->paymentModel->getPayment($payment_id)->get()[0];
        $expense = $this->expensesModel->getExpense($payment->expenses_id)->get()[0];
        $id = $expense->user_id;
        $expenses = $this->expensesModel->getExpenses($id)->get();
        $status = $this->statusModel->getAll();

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
