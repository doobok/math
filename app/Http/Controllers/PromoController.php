<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Option;

class PromoController extends Controller
{
    public function getPromo()
    {
        $price1 = Option::where('name', 'price_1')->pluck('value')->first();
        $price2 = Option::where('name', 'price_2')->pluck('value')->first();
        $discount = Option::where('name', 'discount_percent')->pluck('value')->first();
        $discountg = Option::where('name', 'discount_gr_percent')->pluck('value')->first();

        $data = [
              'price1' => $price1,
              'price2' => $price2,
              'discount' => $discount,
              'discountg' => $discountg,

            ];

        return $data;

    }
}
