<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class GlobalVarProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->userName();
    }

    public function userName()   {

      View::composer('layouts.admin-vue', function ($view) {
        $view->with('userName', \App\User::where('id', Auth::id())->pluck('name')->first() );
      });
    }
}
