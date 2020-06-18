<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainPageController extends Controller
{
    public function index()
    {
      return view('index', [
        // 'price1' => $price1,
      ]);
    }
}
