<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
 
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use Session;
use App\Models\User;
// use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }
    public function login(Request $request)
    {
        $rules = [
            // 'email'                 => 'required|email',
            'password'              => 'required|string'
        ];
 
        $messages = [
            'email.required'        => 'Email wajib diisi',
            'email.email'           => 'Email tidak valid',
            'password.required'     => 'Password wajib diisi',
            'password.string'       => 'Password harus berupa string'
        ];
 
        $validator = Validator::make($request->all(), $rules, $messages);
 
        if($validator->fails()){
            $error  = [];
            $err = json_decode($validator->errors(),1);
            foreach ($err as $key => $value) {
                foreach ($value as $value2) {
                    $error[] = $value2;
                }
            }
            $_SESSION['notif'] = 'true';
            $_SESSION['title'] = 'Login Gagal !';
            $_SESSION['message'] = implode(', ', $error);
            $_SESSION['status'] = 'error';
            return redirect('/admin/login');
        }
 
        $data = [
            'email'     => $request->input('email'),
            'password'  => $request->input('password'),
        ];
        $user = User::where('email',$data['email'])->first();
        if ($user && Hash::check($data['password'], $user->password)) { // true sekalian session field di users nanti bisa dipanggil via Auth
            //Login Success
            $user = User::where('email',$data['email'])->first();
            $_SESSION['user'] = json_decode($user,1);
            $_SESSION['notif'] = 'true';
            $_SESSION['title'] = 'Login Berhasil !';
            $_SESSION['message'] = 'Selamat datang kembali.';
            $_SESSION['status'] = 'success';
            return redirect('/admin');
 
        } else { // false
 
            $_SESSION['notif'] = 'true';
            $_SESSION['title'] = 'Login Gagal !';
            $_SESSION['message'] = 'Email & Password anda tidak cocok.';
            $_SESSION['status'] = 'error';
            return redirect('/admin/login');
        }
 
    }
}
