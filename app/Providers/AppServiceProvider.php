<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS di production (Vercel / shared hosting)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Jika di balik reverse proxy (Vercel), percayai semua proxy
        Request::setTrustedProxies(['*'], Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO);

        View::composer('layouts.app', function ($view) {
            try {
                $categories = Category::select('id', 'name', 'slug')->get();
            } catch (\Exception $e) {
                $categories = collect();
            }
            $view->with('globalCategories', $categories);
        });
    }
}
