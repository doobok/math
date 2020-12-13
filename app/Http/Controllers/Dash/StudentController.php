<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Student;

class StudentController extends Controller
{
    public function getStudent()
    {
      return Student::all();
    }
    // add new Student
    public function setStudent(Request $request)
    {
      $student = Student::create($request->all());

      return response()->json(['success' => 'true', 'data' => $student]);
    }
    // edit Student
    public function updStudent(Request $request, $id)
    {
      $student = Student::findOrFail($id);
      $student->update($request->all());

      return response()->json(['success' => 'true']);
    }
}
