<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Invite;
use App\User;

class InvitesController extends Controller
{
  // Invites
  public function getInvites()
  {
    return Invite::orderBy('created_at', 'desc')->get();
  }

  public function setInvite(Request $request)
  {
    $invite = Invite::create($request->all());

    return response()->json(['success' => true, 'data' => $invite]);
  }

  public function delInvite($id)
  {
    $invite = Invite::findOrFail($id);
    $invite->delete();
    return response()->json(['success' => true]);
  }

  // Users
  public function getUsers()
  {
    return User::where('id', '>', 2)->orderBy('id', 'desc')->select('id', 'name', 'role', 'role_id', 'created_at')->get();
  }

  public function delUser($id)
  {
    $user = User::findOrFail($id);
    $user->delete();
    return response()->json(['success' => true]);
  }
}
