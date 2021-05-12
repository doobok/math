<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Student;
use App\Pay;
use App\Pass;

class StudentController extends Controller
{
    // // дії доступні виключно для адміністратора
    // public function __construct()
    // {
    //     $this->middleware('can:admin');
    // }

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
      $student->balance = 0;

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

    // Публічна частина
    //
    // профіль студента
    public function studentProfile()
    {
      $user = Auth::user();
      if ($user->role == 'student') {
        $student = Student::where('id', $user->role_id)->select('id', 'name', 'lname', 'balance', 'created_at')->firstorfail();;
        return view('admin.students-profile', [
            'student' => $student,
          ]);
      } else {
        return redirect()->back();
      }
    }

    // отримуємо статистику по учню
    public function getStudentSelfStat(Request $request)
    {
      $pays = Pay::where('student_id', $request->id)->where('type', 'lesson-pay')->orderBy('id', 'desc')->get();
      $passes = Pass::where('student_id', $request->id)->orderBy('id', 'desc')->get();

      return response()->json([
        'pays' => $pays,
        'passes' => $passes,
        'pays_cnt' => count($pays),
        'passes_cnt' => count($passes),
      ]);
    }
}
