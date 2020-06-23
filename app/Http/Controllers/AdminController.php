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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $options = Option::all();

        return view('admin.index', [
          'options' => $options,
        ]);
    }
}
