<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Policies\ExpensesPolicy;

class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->userModel = new User();
        $this->expensesPolicy = new ExpensesPolicy();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $id = $this->userModel->getUserAuth();

        if ($this->expensesPolicy->view($id)) {

            $page = $request->get('page', 1);
            $perPage = 4;

            $user = $this->userModel->getUser($id)->get()[0];

            if ($user->isadmin == 1) {

                $expenses = app('App\Http\Controllers\ExpensesController')->index($id, 1);
                $users = $this->userModel->getAll();
                
                foreach ($expenses as $e) {
                    foreach ($users as $u) {
                        if ($u->user_id === $e->user_id) {
                            $e->user = $u->email;
                        }
                    }
                }
                return view('admin.admin', [
                    'expenses' => $expenses->forPage($page, $perPage),
                    'pagination' => \BootstrapComponents::pagination($expenses, $page, $perPage, '', ['arrows' => true]),
                ]);
            } else {

                $expenses = app('App\Http\Controllers\ExpensesController')->index($id, 0);

                return view('user.home', [
                    'expenses' => $expenses->forPage($page, $perPage),
                    'pagination' => \BootstrapComponents::pagination($expenses, $page, $perPage, '', ['arrows' => true]),
                ]);
            }
        }
        
        session()->flash('message', 'Nie posiadasz uprawnień, aby wyświetlić listę wydatków!');
        
        if ($this->userModel->getUser($id)->get()[0]->isadmin === 1) {
            return view('admin.admin', [
                'pagination' => \BootstrapComponents::pagination(null, 0, 4, '', ['arrows' => true]),
            ]);
        } else {
            return view('user.home', [
                'pagination' => \BootstrapComponents::pagination(null, 0, 4, '', ['arrows' => true]),
            ]);
        }
    }

}
