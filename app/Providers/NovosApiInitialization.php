<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;

class NovosApiInitialization extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
       $folderPath = '/novos-text-files';
       Storage::makeDirectory($folderPath);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
