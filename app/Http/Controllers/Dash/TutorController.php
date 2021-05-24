<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tutor;
use App\Pay;

class TutorController extends Controller
{
    // дії доступні виключно для адміністратора
    public function __construct()
    {
        $this->middleware('can:admin');
    }

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
    // отримуємо статистику по тьютору
    public function getTutorStat(Request $request)
    {
      $pays = Pay::where('tutor_id', $request->id)->orderBy('id', 'desc')->limit(100)->get();
      $wage = Pay::where('tutor_id', $request->id)->where('type', 'wage')->get();
      $lessons = Pay::where('tutor_id', $request->id)->where('type', 'lesson-wage')->get();

      // обраховуємо виплачену зп
      $sum = 0;
      foreach ($wage as $pay) {
          $sum = $sum + $pay->sum;
      }

      return response()->json([
        'pays' => $pays,
        'sum' => $sum,
        'lessonscount' => $lessons->count(),
      ]);
    }
}
