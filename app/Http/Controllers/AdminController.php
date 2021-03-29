<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Option;
use App\Student;
use App\Tutor;

class AdminController extends Controller
{
    // дії доступні виключно для адміністратора
    public function __construct()
    {
        $this->middleware('can:admin');
    }

    // students
    public function students()
    {
      return view('admin.students', [
        // 'options' => $options,
      ]);
    }
    public function studentsPage($id)
    {
      $student = Student::findOrFail($id);
      return view('admin.students-page', [
          'student' => $student,
        ]);
    }

    // tutors
    public function tutors()
    {
      return view('admin.tutors', [
        // 'options' => $options,
      ]);
    }
    public function tutorsPage($id)
    {
      $tutor = Tutor::findOrFail($id);
      return view('admin.tutors-page', [
          'tutor' => $tutor,
        ]);
    }

    // classrooms
    public function classrooms()
    {
      return view('admin.classrooms', [
        // 'options' => $options,
      ]);
    }

    // finance
    public function finance()
    {
      return view('admin.finance', [
        // 'options' => $options,
      ]);
    }

    // reports
    public function reports()
    {
      return view('admin.reports', [
        // 'data' => $data,
      ]);
    }

    public function settings()
    {
        $options = Option::all();

        return view('admin.settings', [
          'options' => $options,
        ]);
    }

    // reports
    public function invites()
    {
      return view('admin.invites', [
        // 'options' => $options,
      ]);
    }
}
