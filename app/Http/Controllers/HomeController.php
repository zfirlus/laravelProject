<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        
        $id = \Illuminate\Support\Facades\Auth::user()->user_id;
        
        $page = $request->get('page', 1);
        $perPage = 4;
        
        $user = User::find($id);
        
        if ($user->isadmin == 1) {
            
            $expenses = app('App\Http\Controllers\ExpensesController')->index($id, 1);
            $users = User::all();
            foreach($expenses as $e){
                
                foreach($users as $u){
                    if($u->user_id === $e->user_id){
                        $e->user= $u->email;
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

}
