<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lesson;
use App\Student;
use App\Tutor;
use App\Classroom;

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
}
