<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Pusher\Pusher;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Lesson;
use App\Tutor;
use Carbon\Carbon;

class VideoChatController extends Controller
{
    // rooms dashboard
    public function onlineDash()
    {
      $user = Auth::user();
      $others = User::where('id', '!=', $user->id)->pluck('name', 'id');

      \App\Events\VideoChat::dispatch(['room_id' => 1]);

      return view('admin.online-dash', [
        'user' => collect($user->only(['id', 'name'])),
        'others' => $others
      ]);
    }

    public function onlineRoom($id)
    {
      $user = Auth::user();

      // \App\Events\VideoChat::dispatch(['room_id' => $id]);

      return view('admin.online-room', [
        'user' => $user,
        'room_id' => $id
      ]);
    }

    public function auth(Request $request) {
        $user = Auth::user();
        $socket_id = $request->socket_id;
        $channel_name = $request->channel_name;
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'encrypted' => true
            ]
        );
        return response(
            $pusher->presence_auth($channel_name, $socket_id, $user->id)
        );
    }

    // Отримуємо доступні кімнати
    public function getRooms()
    {
        $user = Auth::user();
        $lessons = Lesson::where('classroom_id', 3)->whereDate('start', Carbon::today()->toDateString())->where('end', '>', Carbon::now()->toDateTimeString())->orderBy('start')->get();
        $rooms = [];
        if ($user->role === 'admin') {
            // для адміністратора повертаємо усі
            $rooms = $lessons;
        } elseif ($user->role === 'tutor') {
            // перебираємо та шукаємо відповідні уроки для тьютора
            foreach ($lessons as $room) {
              if ($room->tutor_id === $user->role_id) {
                array_push($rooms, $room);
              }
            }
        } elseif ($user->role === 'student') {
            // для учнів перебираємо результати
            foreach ($lessons as $room) {
              // отримуємо доступ до поля учнів
              $students = json_decode($room->students);
              // перебираємо і шукаємо відповідності
              foreach ($students as $student) {
                if ($student->id === $user->role_id) {
                    array_push($rooms, $room);
                }
              }
            }
        } else {
            exit;
        }
        return $rooms;
    }

    public function getStartData()
    {
      $tutors = Tutor::select('id', 'name', 'lname', 'mname')->get();

      return response()->json([
        'tutors' => $tutors,
      ]);
    }
}


// <?php
// namespace App\Http\Controllers;
// use Illuminate\Http\Request;
// use Pusher\Pusher;
// class VideoChatController extends Controller
// {
//     public function index(Request $request) {
//         $user = $request->user();
//         $others = \App\User::where('id', '!=', $user->id)->pluck('name', 'id');
//         return view('video_chat.index')->with([
//             'user' => collect($request->user()->only(['id', 'name'])),
//             'others' => $others
//         ]);
//     }
//
//     public function auth(Request $request) {
//         $user = $request->user();
//         $socket_id = $request->socket_id;
//         $channel_name = $request->channel_name;
//         $pusher = new Pusher(
//             config('broadcasting.connections.pusher.key'),
//             config('broadcasting.connections.pusher.secret'),
//             config('broadcasting.connections.pusher.app_id'),
//             [
//                 'cluster' => config('broadcasting.connections.pusher.options.cluster'),
//                 'encrypted' => true
//             ]
//         );
//         return response(
//             $pusher->presence_auth($channel_name, $socket_id, $user->id)
//         );
//     }
// }
