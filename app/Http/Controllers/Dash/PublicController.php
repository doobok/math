<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublicController extends Controller
{
  // доступно лише для авторизованих
  public function __construct()
  {
      $this->middleware('auth');
  }

  // сторінка розкладу
  public function index()
  {
    return view('admin.index', [
      // 'options' => $options,
    ]);
  }
}
