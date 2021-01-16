<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lesson;
use App\Student;
use App\Tutor;
use App\Classroom;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    // доступно лише для авторизованих
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getLesson(Request $request)
    {
      $params = [];

      $user = Auth::user();

      if ($user->role === 'tutor') {
        array_push($params, ['tutor_id', $user->role_id]);
      } elseif ($request->tutor != 'all') {
        array_push($params, ['tutor_id', $request->tutor]);
      }

      // if ($request->tutor != 'all') {
      //   array_push($params, ['tutor_id', $request->tutor]);
      // }

      if ($request->classroom != 'all') {
        array_push($params, ['classroom_id', $request->classroom]);
      }

      $lessons = Lesson::where($params)
        ->where('start', '>', $request->start)
        ->where('end', '<', $request->end)
        ->get();

      return $lessons;
    }
    // start data
    public function getStartData()
    {
      $user = Auth::user();

      if ($user->role === 'tutor') {
        $tutors = Tutor::where('id', $user->role_id)->get();
      } else {
        $tutors = Tutor::where('active', true)->get();
      }
      $students = Student::where('active', true)->get();
      $classrooms = Classroom::where('active', true)->get();

      return response()->json([
        'students' => $students,
        'tutors' => $tutors,
        'classrooms' => $classrooms,
      ]);
    }
    // add new Lesson
    public function setLesson(Request $request)
    {
      // відсікаємо помилковий час закінчення
      if ($request->start > $request->end) {
        return response()->json(['success' => 'false', 'msg' => 'Час завершення не може бути більшим за час початку, заняття не створено!']);
      }
      // відсікаємо заняття які створюються для минулого часу
      if (Carbon::today() > Carbon::create($request->start)) {
        return response()->json(['success' => 'false', 'msg' => 'Неможливо сворити для минулого часу, заняття не створено!']);
      }
      // створюємо заняття
      $lesson = Lesson::create($request->all());
      $lesson->start = $request->start;
      $lesson->end = $request->end;
      $lesson->save();

      return response()->json(['success' => 'true', 'data' => $lesson]);
    }
    // edit Lesson
    public function updLesson(Request $request, $id)
    {
      // відсікаємо помилковий час закінчення
      if ($request->start > $request->end) {
        return response()->json(['success' => 'false', 'msg' => 'Час завершення не може бути більшим за час початку, заняття не збережене!']);
      }
      // відсікаємо заняття які переносяться до минулого часу
      if (Carbon::today() > Carbon::create($request->start)) {
        return response()->json(['success' => 'false', 'msg' => 'Неможливо перемістити в минулий час, заняття не збережене!']);
      }
      // знаходимо заняття
      $lesson = Lesson::findOrFail($id);
      // обновляємо
      $lesson->update($request->all());
      $lesson->start = $request->start;
      $lesson->end = $request->end;
      $lesson->save();

      return response()->json(['success' => 'true', 'data' => $lesson]);
    }
    // edit Lesson time
    public function updLessonTime(Request $request, $id)
    {
      // відсікаємо не адміністратора
      if (Auth::user()->cannot('admin')) {
        return response()->json(['success' => 'false', 'msg' => 'Дія доступна лише для адміністратора!']);
      }

      // відсікаємо заняття які переносяться до минулого часу
      if (Carbon::today() > Carbon::create($request->start)) {
        return response()->json(['success' => 'false', 'msg' => 'Неможливо перемістити в минулий час, заняття не збережене!']);
      }

      $lesson = Lesson::findOrFail($id);
      $lesson->start = Carbon::createFromTimestamp($request->start);
      $lesson->end = Carbon::createFromTimestamp($request->end);
      $lesson->save();

      return response()->json(['success' => 'true', 'data' => $lesson]);
    }
    // copy Lesson
    public function copyLesson($id)
    {
      // // відсікаємо не адміністратора
      // if (Auth::user()->cannot('admin')) {
      //   return response()->json(['success' => 'false', 'msg' => 'Дія доступна лише для адміністратора!']);
      // }

      $lesson = Lesson::findOrFail($id);

      $newLesson = $lesson->replicate();
      $newLesson->pass = null;
      $newLesson->pass_paid = null;
      $newLesson->computed = 0;
      $newLesson->save();

      return response()->json(['success' => 'true', 'data' => $newLesson]);
    }
    // copy Lesson
    public function delLesson($id)
    {
      // // відсікаємо не адміністратора
      // if (Auth::user()->cannot('admin')) {
      //   return response()->json(['success' => 'false', 'msg' => 'Дія доступна лише для адміністратора!']);
      // }

      $lesson = Lesson::findOrFail($id);

      $lesson->delete();

      return response()->json(['success' => 'true']);

    }
}
