<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
// use Illuminate\Validation\ValidationException;
use App\User;
// use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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

    // use RegistersUsers;

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

    public function register(Request $request)
    {
      $request->validate([
          'name'                => 'required|unique:users',
          'email'               => 'required|email|unique:users',
          'password'            => 'required|min:6|confirmed',
          'avatar'              => '',
      ], [
          'password.confirmed'  => 'The password does not match.'
      ]);

      $avatar = $request->avatar;

      $user = $this->create(array_filter($request->all()));
      ($user && $avatar) && $user->saveImage($avatar, 'avatar');
      try {
        // $user->notify(new SignupActivate($user));
      } catch (\Exception $e) {
        // $user->active = 1;
        // $user->save();
      }

      $tokenResult = $user->createToken('UAT');
      $token       = $tokenResult->token;
      if ($request->remember_me)
          $token->expires_at = Carbon::now()->addWeeks(1);
      $token->save();

      return response()->json([
          'message'     => 'Successfully created user!',
          'user'        => $user,
          'token'       => $tokenResult->accessToken,
          'token_type'  => 'Bearer',
          'expires_at'  => Carbon::parse(
              $tokenResult->token->expires_at
          )->toDateTimeString()
      ], 201);
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
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
