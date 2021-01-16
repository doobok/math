<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Invite;

class AuthViewsController extends Controller
{
    // register
    public function register($ivite)
    {
      // перевіряємо валідність запрошення
      $valid = Invite::where('invite', $ivite)->firstorfail();

      return view('admin.auth.register', [
        'invite' => $ivite,
      ]);
    }

    public function checkName(Request $request)
    {
       $user = User::where('name', $request->name)->first();
       $code = 0;
       if ($user) {
         $code = 200;
       } else {
         $code = 404;
       }

       return response()->json(['status' => $code]);
    }
}
