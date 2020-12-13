<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tutor;

class TutorController extends Controller
{
    public function getTutor()
    {
      return Tutor::all();
    }
    // add new Tutor
    public function setTutor(Request $request)
    {
      $tutor = Tutor::create($request->all());

      return response()->json(['success' => 'true', 'data' => $tutor]);
    }
    // edit Tutor
    public function updTutor(Request $request, $id)
    {
      $tutor = Tutor::findOrFail($id);
      $tutor->update($request->all());

      return response()->json(['success' => 'true']);
    }
}
