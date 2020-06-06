<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Carbon\Carbon;

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

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
      $path     = $request->getPathInfo();
      $is_admin = $path == '/api/admin/login';
      $guard    = $is_admin ? 'api:admin' : 'api';

      $request->validate([
        'email'       => 'required|email',
        'password'    => 'required|string',
        'remember_me' => 'boolean'
      ]);
      $credentials = request(['email', 'password']);

      if(!Auth::guard($guard)->check($credentials))
        return response()->json([
            'message' => 'credentials does not match our records',
            'status'  => false,
        ], 401);

      $user = $request->user($guard);
      $user->withUrls('avatar');

      $token       = $user->grantMeToken();

      return response()->json([
          'user'        => $user,
          'token'       => $token['token'],
          'token_type'  => $token['token_type'],
          'expires_at'  => $token['expires_at'],
      ]);
    }

    public function logout(Request $request)
    {
      $request->user()->token()->revoke();
      return response()->json([
          'message' => 'Successfully logged out'
      ]);
    }
}
