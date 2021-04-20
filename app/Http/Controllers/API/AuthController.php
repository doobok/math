<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Invite;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register()
    {
      // отримуємо інвайт з БД
      $invite = Invite::where('invite', request('invite'))->firstorfail();
      // створюємо користувача
      $user = User::create([
              'name' => request('name'),
              'realname' => $invite->name,
              'password' => bcrypt(request('password')),
              'role' => $invite->role,
              'role_id' => $invite->role_id,
      ]);
      // знищуємо інвайт, щоб уникнути повторної реєстрації
      $invite->delete();

      Auth::login($user);

      return response()->json(['status' => 201]);
    }

    public function logout()
    {
        Auth::logout();

        return redirect('login');
    }

    // public function login()
    // {
    //     $client = DB::table('oauth_clients')
    //         ->where('password_client', true)
    //         ->first();
    //
    //     $data = [
    //         'grant_type' => 'password',
    //         'client_id' => $client->id,
    //         'client_secret' => $client->secret,
    //         'username' => request('username'),
    //         'password' => request('password'),
    //     ];
    //
    //     $request = Request::create('/oauth/token', 'POST', $data);
    //
    //     return app()->handle($request);
    // }

    // public function logout()
    // {
    //     $accessToken = auth()->user()->token();
    //
    //     $refreshToken = DB::table('oauth_refresh_tokens')
    //         ->where('access_token_id', $accessToken->id)
    //         ->update([
    //             'revoked' => true
    //         ]);
    //
    //     $accessToken->revoke();
    //
    //     return response()->json(['status' => 200]);
    // }



}
