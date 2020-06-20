<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class MainPageController extends Controller
{
    public function index()
    {
      return view('index', [
        // 'price1' => $price1,
      ]);
    }

    public function indexUK()
    {
        // зададим локаль UK
        App::setLocale('uk');
        // перебросим на основной метод
        return self::index();
    }
}
