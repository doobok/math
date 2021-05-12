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

    // функція онлайн кімнати заняття
    public function onlineRoom($id)
    {
      $user = Auth::user();
      $lesson = Lesson::findOrFail($id);

      $approved = false;

      // check user
      switch ($user->role) {
        case 'admin':
          $approved = true;
          break;
        case 'tutor':
          if ($user->role_id === $lesson->tutor_id) {
            $approved = true;
          }
          break;
        case 'student':
          $students = json_decode($lesson->students);
          foreach ($students as $student) {
            if ($student->id === $user->role_id) {
              $approved = true;
            }
          }
          break;
        default:
          $approved = false;
          break;
      }

      // check date
      if ($lesson->end < Carbon::now()->toDateTimeString()) {
        $approved = false;
      }

      if ($approved) {
        return view('admin.online-room', [
          'user' => $user,
          'room_id' => $id,
        ]);
      } else {
        return view('admin.online-no-room');
      }
    }

    // отримуємо потрібні параметри часу
    public function getRoomTimes(Request $request)
    {
      $lesson = Lesson::findOrFail($request->id);

      return response()->json([
        'now' => strtotime(Carbon::now()->toDateTimeString()) * 1000,
        'start' => $lesson->start,
        'end' => strtotime(Carbon::create($lesson->end)->toDateTimeString()) * 1000,
      ]);
      // code...
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
