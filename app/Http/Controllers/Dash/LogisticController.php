<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pay;
use App\Report;

class LogisticController extends Controller
{
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
}
