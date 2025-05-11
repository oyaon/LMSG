<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

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
        // Set default string length for MySQL older than 5.7.7
        Schema::defaultStringLength(191);
        
        // Use Bootstrap pagination
        Paginator::useBootstrap();
        
        // Prevent lazy loading in production
        Model::preventLazyLoading(!app()->isProduction());
        
        // Register view composers
        $this->registerViewComposers();
    }
    
    /**
     * Register view composers.
     */
    private function registerViewComposers(): void
    {
        // Share categories with all views
        view()->composer('*', function ($view) {
            $view->with('allCategories', \App\Models\Book::getCategories());
        });
        
        // Share cart count with all views
        view()->composer('*', function ($view) {
            if (auth()->check()) {
                $cartCount = \App\Models\Cart::where('user_id', auth()->id())
                    ->where('status', 0)
                    ->count();
                
                $view->with('cartCount', $cartCount);
            }
        });
    }
}