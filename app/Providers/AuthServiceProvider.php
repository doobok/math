<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Policies\DashboardPolicy',
    ];

    public function DashboardRules()
    {
      Gate::before(function ($user) {
        if ($user->id === 2 || $user->role === 'admin') {
          return true;
        }
      });
      Gate::define('admin', function ($user) {
          return $user->role === 'admin';
      });
      Gate::define('tutor', function ($user) {
          return $user->role === 'tutor';
      });
      Gate::define('student', function ($user) {
          return $user->role === 'student';
      });
    }

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->DashboardRules();

        Passport::routes(function ($router) {
            $router->forAccessTokens();
        });

    }
}
