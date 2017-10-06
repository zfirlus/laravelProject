<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Policies\UserPolicy;

class UserController extends Controller {

    public function __construct() {
        $this->userModel = new User();
        $this->userPolicy = new UserPolicy();
    }

    public function index(Request $request) {
        if ($this->userPolicy->view($this->userModel->getUserAuth())) {
            $id = $this->userModel->getUserAuth();
            
            $users = $this->userModel->getAll();

            $page = $request->get('page', 1);
            $perPage = 4;

            return view('admin.users', [
                'users' => $users->forPage($page, $perPage),
                'pagination' => \BootstrapComponents::pagination($users, $page, $perPage, '', ['arrows' => true]),
                'id' => $id,
            ]);
        }
        session()->flash('message', 'Nie posiadasz uprawnień, aby wyświetlić listę użytkowników!');
        return view('admin.users', [
            'pagination' => \BootstrapComponents::pagination(null, 0, 4, '', ['arrows' => true]),
        ]);
    }

    public function register() {
        return View('auth.register');
    }

    public function create(Request $data) {
        if ($this->userPolicy->create($this->userModel->getUserAuth())) {
            $messages = [
                'email:unique' => 'Podany adres już istnieje!',
                'email' => 'Niepoprawny adres email!',
                'email.max' => 'Adres email nie może przekraczać 30 znaków!',
                'email.min' => 'Adres email musi mieć przynajmniej 6 znaków!',
                'email.required' => 'Pole nie może być puste!',
                'password.required' => 'Pole nie może być puste!',
                'password.min' => 'Hasło musi mieć przynajmniej 6 znaków!',
                'password.max' => 'Hasło nie może przekraczać 20 znaków!',
                'confirmed' => 'Hasła się nie zgadzają!',
            ];

            $this->validate($data, [
                'email' => 'required|min:6|string|email|max:30|unique:users',
                'password' => 'required|string|min:6|max:20|confirmed',
                    ], $messages);

            $this->userModel->createUser($data);
            
            return redirect()->route('users');
        }
        session()->flash('message', 'Nie posiadasz uprawnień, aby dodać nowego użytkownika!');
        return redirect()->route('users');
    }

    public function editForm($user_id) {
        
        $users = $this->userModel->getUser($user_id)->get();
        $user = $users[0];
        $user->pass = '';
        $user->oldpassword = '';

        return View('admin.edituser', compact('user', 'user'));
    }

    public function showLogin() {
        return View('index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        
    }

    public function show(Users $users) {
        return view('index', ['users' => $users]);
    }

    public function edit(Request $request) {
        if ($this->userPolicy->update($this->userModel->getUserAuth())) {
            $messages = [
                'equal' => 'Niepoprawne hasło!',
                'newpassword' => 'To hasło jest aktualne!',
                'email:unique' => 'Podany adres już istnieje!',
                'email' => 'Niepoprawny adres email!',
                'email.max' => 'Adres email nie może przekraczać 30 znaków!',
                'email.min' => 'Adres email musi mieć przynajmniej 6 znaków!',
                'password.required' => 'Pole nie może być puste!',
                'password.min' => 'Hasło musi mieć przynajmniej 6 znaków!',
                'password.max' => 'Hasło nie może przekraczać 20 znaków!',
                'confirmed' => 'Hasła się nie zgadzają!',
            ];

            $pass = $request['pass'];
            $oldpass = $request['oldpassword'];
            $new = $request['password'];

            if ($request['email']) {

                $this->validate($request, [
                    'email' => 'min:6|string|email|max:30|unique:users',
                        ], $messages);

                $this->userModel->editUserEmail($request);
            }
            if ($request['oldpassword']) {

                $this->validate($request, [
                    'oldpassword' => 'equal:' . $oldpass . ',' . $pass,
                    'password' => 'required|string|min:6|max:20|newpassword:' . $new . ',' . $pass . '|confirmed:password_confirmation',
                        ], $messages);
                
                $this->userModel->editUserPassword($request);
            }
            return redirect()->route('users');
        }
        session()->flash('message', 'Nie posiadasz uprawnień, aby edytować użytkownika!');
        return redirect()->route('users');
    }

    public function update(Request $request, User $user) {
        //
    }

    public function destroy(User $user) {
        
    }

}
