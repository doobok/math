<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lead;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TelegramNewLead;
use Carbon\Carbon;

class LeadsController extends Controller
{
  // захват лида
  public function addLead(Request $request)
  {
      $this->validate($request, [
          'tempy' => 'required',
      ]);

      // проверяем наличие лида в БД
      $lead = Lead::where('tempy', $request->tempy)->orderBy('id', 'desc')->first();

      // если нет лида в БД то создаем нового
      if ($lead === null) {

        // обрабатываем
        return self::saveToDB($request);

      } else {
        // блокируем отправку на n часов
         $now = Carbon::now();
         // если время в БД+n часов больше реального - блокируем
        if ($lead->created_at->addHours(5) > $now) {

          return response()->json(['success' => 'false', 'msg' => __('site.phone-sended-alert')]);

        } else {
          // иначе обрабатываем
          return self::saveToDB($request);
        }
      }
  }

  public function saveToDB($request)
  {
      $lead = new Lead;

      $lead->phone = $request->phone;
      $lead->tempy = $request->tempy;

      $lead->save();

      // notifications
        // формируем сообщение
        $phone = '+38' . $request->phone;
        $slug = $request->slug;
        //telegram notification
        Notification::send('', new TelegramNewLead($slug, $phone));

        // отправляем в retailCRM
        $client = new \RetailCrm\ApiClient(
            config('app.retailcrm_url'),
            config('app.retailcrm_api'),
            \RetailCrm\ApiClient::V4
        );

        try {
            $response = $client->request->ordersCreate(array(
                // 'summ' => $request->price, //цена
                'phone' => $phone, // телефон
                'customerComment' => $request->slug, // тайтл кнопки
                'discountPercent' => $request->discount, // скидка в процентах
                'items' => array(
                  array(
                    'initialPrice' => $request->price, // цена заказа
                  )
                ),
                'customFields' => array(
                    'group_classes' => $request->group
                )
            ));
        } catch (\RetailCrm\Exception\CurlException $e) {
            // echo "Connection error: " . $e->getMessage();
        }


      return response()->json(['success' => 'true']);
  }
}
