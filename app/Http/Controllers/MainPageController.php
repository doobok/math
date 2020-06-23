<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Option;
use Carbon\Carbon;

class MainPageController extends Controller
{
    public function index()
    {
      // options
      $collection = Option::all();
      $options = $collection->keyBy('name');

      // updated time
      $updated = Option::all()->pluck('created_at')->last();

      // promo time
      $promo_time = null;
      if ($options->has('promo_time')) {
        // получаем переменные времени
        $now = Carbon::now();
        $time = $options->get('promo_time')->value;

        if ($time > $now) {
          // если время з БД больше чем актуальное - отдаем время из БД
          $promo_time = $time;
        } else {
          // получаем переменную на 3 дня больше чем в БД
          $na_date = Carbon::parse($time)->addDays(3);
          // как только реальное время станет больше за эту переменную
          if ($na_date < $now) {
            // создаем переменную для записи в БЗ
            $new_date = Carbon::parse($time)->addDays(7);
            // Находим и обновляем запись в БД
            $option = Option::where('name', '=', 'promo_time')->firstOrFail();
            $option->value = $new_date;
            $option->save();
            // дополнительно выводим переменную
            $promo_time = $new_date;
          }
        }
      }


      return view('index', [
        'options' => $options,
        'updated' => $updated,
        'promo_time' => $promo_time,
      ]);
    }

    public function indexUK()
    {
        // зададим локаль UK
        App::setLocale('uk');
        // перебросим на основной метод
        return self::index();
    }

    public function policy()
    {
      return view('policy');
    }
}
