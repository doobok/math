<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Option;

class OptionsController extends Controller
{
    public function create(Request $request)
    {
      $this->validate($request, [
            'name' => 'required|unique:options',
        ]);

      Option::create($request->all());

      return back();
    }

    public function update(Request $request, $id)
    {
      $option = Option::findOrFail($id);
      $option->value = $request->value;
      $option->save();

      return back();
    }
}
