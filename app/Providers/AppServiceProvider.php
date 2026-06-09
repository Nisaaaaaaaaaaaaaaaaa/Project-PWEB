<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan; // HAPUS baris ini jika ada

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // HAPUS atau COMMENT kode ini:
        // if (app()->environment('production')) {
        //     Artisan::call('migrate --force');
        // }
    }
}