<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pay;
use App\Report;

class LogisticController extends Controller
{
    // // дії доступні виключно для адміністратора
    // public function __construct()
    // {
    //     $this->middleware('can:admin');
    // }
    // get Finances operations
    public function getFinances(Request $request)
    {
      return Pay::skip($request->skip)->limit(50)->orderBy('created_at', 'desc')->get();
    }

    // get Reports
    public function getReports(Request $request)
    {
      return Report::skip($request->skip)->limit(50)->orderBy('created_at', 'desc')->get();
    }

    // поповнюємобаланс студента
    public function refillStud(Request $request)
    {
      $refill = new Pay;
      $refill->sum = $request->sum;
      $refill->student_id = $request->id;
      $refill->type = 'refill';
      $refill->save();

      return response()->json(['success' => true ]);
    }

    // виплата зарплати
    public function wagePay(Request $request)
    {
      $wage = new Pay;
      $wage->sum = $request->sum;
      $wage->tutor_id = $request->id;
      $wage->type = 'wage';
      $wage->save();

      return response()->json(['success' => true, 'pay' => $wage ]);
    }

    // внесення інших платежів
    public function otherPay(Request $request)
    {
      $pay = new Pay;
      $pay->sum = $request->sum;
      $pay->comment = $request->comment;
      $pay->type = 'other-pay';
      $pay->save();

      return response()->json(['success' => true, 'pay' => $pay ]);
    }
}
