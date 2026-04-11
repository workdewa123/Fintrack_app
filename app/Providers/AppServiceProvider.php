<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengingat;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void { }

    public function boot(): void
    {
        // Membagikan data tagihan mendatang ke view layout.app secara global
        View::composer('layout.app', function ($view) {
            if (Auth::check()) {
                $bulan = now()->month;
                $tagihanGlobal = Pengingat::whereHas('rekening', function ($q) {
                        $q->where('id_pengguna', Auth::id());
                    })
                    ->whereMonth('tanggal_mulai', '<=', $bulan)
                    ->get();
                
                $view->with('tagihanGlobal', $tagihanGlobal);
            }
        });
    }
}