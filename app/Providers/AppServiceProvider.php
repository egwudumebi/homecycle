<?php

namespace App\Providers;

use App\Http\Resources\Api\V1\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        View::composer('web.layouts.app', function ($view) {
            $data = $view->getData();
            if (array_key_exists('categories', $data)) {
                return;
            }

            $categories = CategoryResource::collection(app(CategoryService::class)->getActiveWithSubCategories())
                ->resolve(request());

            $view->with('categories', $categories);
        });
    }
}
