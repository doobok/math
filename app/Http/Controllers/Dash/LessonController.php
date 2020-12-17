<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lesson;
use App\Student;
use App\Tutor;
use App\Classroom;
use Carbon\Carbon;

class LessonController extends Controller
{
    public function getLesson()
    {
      return Lesson::all();
    }
    // start data
    public function getStartData()
    {
      $students = Student::where('active', true)->get();
      $tutors = Tutor::where('active', true)->get();
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
      $lesson = Lesson::create($request->all());

      return $lesson;
    }
    // edit Lesson
    public function updLesson(Request $request, $id)
    {
      $lesson = Lesson::findOrFail($id);
      $lesson->update($request->all());

      return $lesson;
    }
    // edit Lesson time
    public function updLessonTime(Request $request, $id)
    {
      $lesson = Lesson::findOrFail($id);
      $lesson->start = Carbon::createFromTimestamp($request->start);
      $lesson->end = Carbon::createFromTimestamp($request->end);
      $lesson->save();

      return $lesson;
    }
    // copy Lesson
    public function copyLesson($id)
    {
      $lesson = Lesson::findOrFail($id);

      $newLesson = $lesson->replicate();
      $newLesson->pass = null;
      $newLesson->pass_paid = null;
      $newLesson->computed = 0;
      $newLesson->save();

      return $newLesson;
    }
    // copy Lesson
    public function delLesson($id)
    {

      // // удаление доступно только для менеджера
      //   if ($request->user()->cannot('manager-show')) {
      //       abort(403);
      //   }

      $lesson = Lesson::findOrFail($id);

      $lesson->delete();

      return response()->json(['success' => 'true']);

    }
}
