<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;


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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'name';
    }

    // кастомний метод авторизації
    public function login(Request $request)
    {
        // перевіряємо існування користувача
        $user = User::where('name', $request->name)->first();
        if (!$user) {
          return response()->json(['status' => 406, 'msg' => 'Користувача із таким логіном не існує.']);
        }
        // пробуємо його залогінити
        if (Auth::attempt(['name' => $request->name, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();

            if (Auth::user()->role == 'student') {
              $url = '/my-profile';
            } else {
              $url =  '/admin';
            }

            return response()->json(['status' => 201, 'url' => $url]);
        }
        // якщо не вдалося повертаємо повідомлення про невірний пароль
        return response()->json(['status' => 406, 'msg' => 'Не вірний пароль, перевірте правильність вводу та спробуйте ще раз.']);

    }

    protected function redirectTo ()
    {
      if (Auth::user()->role == 'student') {
        return '/my-profile';
      } else {
        return '/admin';
      }
    }
}
