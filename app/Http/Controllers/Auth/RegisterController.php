<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    // protected function create(array $data)
    // {
    //   // dd($data);
    //
    //   event(new Registered($data['email']));
    //     return User::create([
    //         'name' => $data['name'],
    //         'email' => $data['email'],
    //         'lastname' => $data['lastname'],
    //         'phone' => $data['phone'],
    //         'gender' => $data['gender'],
    //         'country' => $data['country'],
    //         'birth' => $data['birth'],
    //         'nit' => $data['nit'],
    //         'termsand' => (!empty($data['termsand'])?'1':'0'),
    //         'password' => 'pastest',
    //     ]);
    // }
    // public function register(Request $request)
    // {
    //   $data = $request->toArray();
    //   // dd($data);
    //    $user = User::create([
    //            'name' => $data['name'],
    //            'email' => $data['email'],
    //            'lastname' => $data['lastname'],
    //            'phone' => $data['phone'],
    //            'gender' => $data['gender'],
    //            'country' => $data['country'],
    //            'birth' => $data['birth'],
    //            'nit' => $data['nit'],
    //            'termsand' => (!empty($data['termsand'])?'1':'0'),
    //            'password' =>  Hash::make($data['password']),
    //        ]);
    //
    //    event(new Registered($user));
    //
    //    auth()->login($user);
    //
    //    return redirect('/home')->with('success', "Account successfully registered.");
    // }
}
