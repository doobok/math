<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Option;

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

    // tutors
    public function tutors()
    {
      return view('admin.tutors', [
        // 'options' => $options,
      ]);
    }

    // classrooms
    public function classrooms()
    {
      return view('admin.classrooms', [
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
