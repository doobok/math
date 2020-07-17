<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rating;

class RatingController extends Controller
{
      // get star rating
      public function getRating(Request $request)
      {
        $rating = Rating::where([
          'model' => $request->model,
          'itemid' => $request->itemid
          ])->first();
        if ($rating === null) {
          $rating = 5;
        }

          return $rating;
      }

      // set vote to rating
      public function setRating(Request $request)
      {
          // пробуем получить рейтинг
          $rating = Rating::where('model', $request->model)->where('itemid', $request->itemid)->first();
          // если не найден
          if ($rating === null) {
            // создаем
            $rating = new Rating;

            $rating->model = $request->model;
            $rating->itemid = $request->itemid;
            $rating->rating = $request->rating;
            $rating->count = 2;

            $rating->save();

            // иначе обновляем рейтинг
          } else {
            $rating->rating = $this->compRating($rating->rating, $request->rating, $rating->count);
            $rating->count++;
            $rating->save();

          }

          return response()->json(['success' => 'true']);
      }

      // калькулятор рейтинга
      public function compRating($rating, $vote, $count)
      {
        return (($rating * $count) + $vote) / ($count + 1);
      }
}
