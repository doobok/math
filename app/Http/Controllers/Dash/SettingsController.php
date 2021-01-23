<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Option;
use Telegram\Bot\Laravel\Facades\Telegram;

class SettingsController extends Controller
{
    public function getSettings()
    {
      return Option::where('name', '!=', 'telegram_webhook_status')->get();
    }

    public function setSetting(Request $request)
    {
      $this->validate($request, [
            'name' => 'required|unique:options',
        ]);

      $setting = Option::create($request->all());

      return response()->json(['success' => 'true', 'data' => $setting]);
    }

    public function updSetting(Request $request, $id)
    {
      $setting = Option::findOrFail($id);
      $setting->description = $request->description;
      $setting->value = $request->value;
      $setting->save();

      return response()->json(['success' => 'true']);
    }

    public function getTelegramSettings()
    {
      $status = Option::where('name', 'telegram_webhook_status')->select('value')->firstorfail();

      return response()->json(['success' => 'true', 'status' => $status->value ]);
    }

    public function getTelegramSetWebhook(Request $request)
    {
      $uri = 'https://bucha.tutor-math.com.ua/' . Telegram::getAccessToken();
      $response = Telegram::setWebhook(['url' => $uri]);

      if ($response) {
        $status = Option::where('name', 'telegram_webhook_status')->firstorfail();
        $status->value = 1;
        $status->save();
        return response()->json(['success' => true]);
      }
    }

    public function getTelegramDelWebhook()
    {
      $response = Telegram::removeWebhook();

      if ($response) {
        $status = Option::where('name', 'telegram_webhook_status')->firstorfail();
        $status->value = 0;
        $status->save();
        return response()->json(['success' => true]);
      }
    }

    public function getTelegramGetWebhook()
    {
      // $response = Telegram::getMe();
      // $response = Telegram::getAccessToken();
      $response = Telegram::getWebhookInfo();

      if ($response) {
        $status = Option::where('name', 'telegram_webhook_status')->firstorfail();
        if ($response->url) {
          $status->value = 1;
        } else {
          $status->value = 0;
        }
        $status->save();

        return $response->url;
      }
    }
}
