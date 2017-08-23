<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all()->toArray();
        
        return View('user', compact('users'));
    }

    public function create()
    {
        return view('user.create');
    }
    
    public function showLogin(){
        return View('index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User([
          'email' => $request->get('email'),
          'password' => $request->get('password'),
          'isadmin' => '0',
        ]);

        $user->save();
        return true;
    }

    public function show(Users $users)
    {
        return view('index',['users' => $users]);
    }

    public function edit(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        
    }
}
