<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classroom;

class ClassroomController extends Controller
{
    // дії доступні виключно для адміністратора
    public function __construct()
    {
        $this->middleware('can:admin');
    }
    
    public function getClass()
    {
      return Classroom::all();
    }
    // add new Classroom
    public function setClass(Request $request)
    {
      $classroom = Classroom::create($request->all());

      return response()->json(['success' => 'true', 'data' => $classroom]);
    }
    // edit Classroom
    public function updClass(Request $request, $id)
    {
      $classroom = Classroom::findOrFail($id);
      $classroom->update($request->all());

      return response()->json(['success' => 'true']);
    }
}
