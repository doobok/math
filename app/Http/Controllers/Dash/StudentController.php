<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Student;
use App\Pay;
use App\Pass;

class StudentController extends Controller
{
    public function getStudent()
    {
      return Student::orderBy('balance')->get();
    }
    // add new Student
    public function setStudent(Request $request)
    {
      $student = Student::create($request->all());
      // конкатенація імені і прізвища
      $student->concname = $request->lname . ' ' . $request->name;
      $student->save();

      return response()->json(['success' => 'true', 'data' => $student]);
    }
    // edit Student
    public function updStudent(Request $request, $id)
    {
      $student = Student::findOrFail($id);
      $student->update($request->all());
      // конкатенація імені і прізвища
      $student->concname = $request->lname . ' ' . $request->name;
      $student->save();

      return response()->json(['success' => 'true']);
    }
    // отримуємо статистику по учню
    public function getStudentStat(Request $request)
    {
      $pays = Pay::where('student_id', $request->id)->orderBy('id', 'desc')->get();
      $passes = Pass::where('student_id', $request->id)->orderBy('id', 'desc')->get();

      return response()->json([
        'pays' => $pays,
        'passes' => $passes,
      ]);
    }
}
