<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //\Illuminate\Support\Facades\URL::forceScheme('https');
        View::share('action','');
        View::share('id',"");
        View::share('main_action',"");
        View::share('base_url', url('/'));
        View::share('title', 'Questanya');
        View::share('content', '');
        View::share('url', url('/').'i/mages/q_icon.svg');
        View::share('img', '');
        
    }
}
