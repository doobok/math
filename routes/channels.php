<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('room.{room_id}', function ($user, $room_id) {
//     return $user->name;
//     // return (int) $user->id === (int) $id;
// });

Broadcast::channel('room.1', function ($user) {
  if ($user) {
    return $user;
  }
});
