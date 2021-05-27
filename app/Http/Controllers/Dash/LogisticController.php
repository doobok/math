<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pay;
use App\Report;
use App\Performance;

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

    // get Statistic for charts
    public function getStats(Request $request)
    {
      $data = collect();

      if ($request->type == 'productivity') {
        $reports = Report::where('type', $request->period)->select('period', 'lessons', 'wage', 'profit')->latest()->take($request->count)->get();
        $data->push(array('period', 'За уроки', 'Дохід тьюторів', 'Профіт'));
      } elseif ($request->type == 'attendance') {
        $reports = Report::where('type', $request->period)->select('period', 'lessons_count', 'students_count', 'pass_count', 'pass_notpayed_count')->latest()->take($request->count)->get();
        $data->push(array('period', 'Кількість уроків', 'Кількість учнів', 'Кількість пропусків', 'Неоплатних пропусків'));
      } elseif ($request->type == 'finances') {
        $reports = Report::where('type', $request->period)->select('period', 'pays_in', 'pays_out', 'pays_profit')->latest()->take($request->count)->get();
        $data->push(array('period', 'Надходження', 'Видатки', 'Профіт'));
      }

      $sorted = $reports->reverse();

      foreach ($sorted as $report) {
        $data->push(array_values($report->toArray()));
      }
      return $data;
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

    // отримуємо KPI
    public function getKPI(Request $request)
    {
      $data = collect();

      if ($request->model == 'student') {
        if ($request->f == 'l') {
          $reports = Performance::where('student_id', $request->id)->select('created_at', 'lessons')->latest()->take(30)->get();
          $data->push(array('d', 'Кількість занять'));
        } elseif ($request->f == 'p') {
          $reports = Performance::where('student_id', $request->id)->select('created_at', 'company_profit')->latest()->take(30)->get();
          $data->push(array('d', 'Профіт компанії'));
        }  elseif ($request->f == 'kpi') {
          $reports = Performance::where('student_id', $request->id)->select('created_at', 'kpi')->latest()->take(30)->get();
          $data->push(array('d', 'KPI (відношення профіту до кількості уроків за одиницю часу)'));
        }
        // $reports = Performance::where('student_id', $request->id)->select('created_at', 'lessons', 'company_profit', 'kpi')->latest()->take(30)->get();
        // $data->push(array('date', 'Кількість занять', 'Профіт компанії', 'KPI (відношення профіту до кількості уроків за одиницю часу)'));
      } elseif ($request->model == 'tutor') {
        if ($request->f == 'l') {
          $reports = Performance::where('tutor_id', $request->id)->select('created_at', 'lessons')->latest()->take(30)->get();
          $data->push(array('d', 'Кількість занять'));
        } elseif ($request->f == 'p') {
          $reports = Performance::where('tutor_id', $request->id)->select('created_at', 'company_profit', 'tutor_profit')->latest()->take(30)->get();
          $data->push(array('d', 'Профіт компанії', 'Дохід тьютора'));
        }  elseif ($request->f == 'kpi') {
          $reports = Performance::where('tutor_id', $request->id)->select('created_at', 'kpi')->latest()->take(30)->get();
          $data->push(array('d', 'KPI (відсоток доходу компанії)'));
        }
        // $reports = Performance::where('tutor_id', $request->id)->select('lessons', 'company_profit', 'tutor_profit', 'kpi')->latest()->take(30)->get();
        // $data->push(array('Кількість занять', 'Профіт компанії', 'Дохід тьютора', 'KPI (відсоток доходу компанії)'));
      }

      $sorted = $reports->reverse();

      foreach ($sorted as $report) {
        $data->push(array_values($report->toArray()));
      }
      return $data;
    }
}
