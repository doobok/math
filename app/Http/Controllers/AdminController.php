<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Option;
use App\Student;
use App\Tutor;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
      return view('admin.index', [
        // 'options' => $options,
      ]);
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
        // 'options' => $options,
      ]);
    }

    public function settings()
    {
        $options = Option::all();

        return view('admin.settings', [
          'options' => $options,
        ]);
    }
}
