<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\BookRepository;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use App\Repositories\AuthorRepository;
use App\Repositories\Interfaces\BorrowRepositoryInterface;
use App\Repositories\BorrowRepository;
use App\Repositories\Interfaces\CartRepositoryInterface;
use App\Repositories\CartRepository;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\PaymentRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            BookRepositoryInterface::class,
            BookRepository::class
        );
        
        $this->app->bind(
            AuthorRepositoryInterface::class,
            AuthorRepository::class
        );
        
        $this->app->bind(
            BorrowRepositoryInterface::class,
            BorrowRepository::class
        );
        
        $this->app->bind(
            CartRepositoryInterface::class,
            CartRepository::class
        );
        
        $this->app->bind(
            PaymentRepositoryInterface::class,
            PaymentRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}