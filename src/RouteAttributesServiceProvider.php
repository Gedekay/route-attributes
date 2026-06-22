<?php

namespace RouteAttributes;

use Illuminate\Support\ServiceProvider;

class RouteAttributesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->scanControllers();
    }

    protected function scanControllers(): void
    {
        RouteScanner::scan(
            app_path('Http/Controllers'),
            'App\\Http\\Controllers'
        );
    }
}