<?php

namespace App\Providers;

use App\Models\Category;
use App\Services\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('layouts.partials.header', function ($view) {
            $cart = app(CartService::class);

            $view->with([
                'headerCategories' => Category::all(),
                'headerCartItems' => $cart->items(),
                'headerCartCount' => $cart->count(),
                'headerCartTotal' => $cart->total(),
            ]);
        });
    }
}
