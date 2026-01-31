<?php

namespace App\Providers;

use App\Models\Borrowing;
use App\Models\Report;
use App\Models\Review;
use App\Policies\BorrowingPolicy;
use App\Policies\ReportPolicy;
use App\Policies\ReviewPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Borrowing::class => BorrowingPolicy::class,
        Report::class => ReportPolicy::class,
        Review::class => ReviewPolicy::class,
    ];

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
        //
    }
}
