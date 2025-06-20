<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

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
        //użycie paginacji bootstrapa
        Paginator::useBootstrapFive();

        Validator::extend('unique_ingredients', function ($attribute, $value, $parameters, $validator) {
            $ids = array_column($value, 'ingredient_id');
            return count($ids) === count(array_unique($ids));
        });

        Validator::replacer('unique_ingredients', function ($message, $attribute, $rule, $parameters) {
            return 'Składniki nie mogą się powtarzać.';
        });
    }
}
