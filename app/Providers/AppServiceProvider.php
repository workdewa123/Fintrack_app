<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengingat;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void { }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Ambil semua pengingat, lalu filter menggunakan PHP (Collection)
            $tagihanGlobal = Pengingat::all()->filter(function ($item) {
                // Jika belum pernah dibayar sama sekali, tampilkan!
                if (is_null($item->tanggal_bayar_terakhir)) {
                    return true;
                }

                $terakhirBayar = Carbon::parse($item->tanggal_bayar_terakhir);

                // Cek berdasarkan frekuensi
                if ($item->frekuensi === 'HARIAN') {
                    return !$terakhirBayar->isToday(); // Muncul jika belum bayar hari ini
                }
                if ($item->frekuensi === 'MINGGUAN') {
                    return !$terakhirBayar->isCurrentWeek(); // Muncul jika belum bayar minggu ini
                }
                if ($item->frekuensi === 'BULANAN') {
                    return !$terakhirBayar->isCurrentMonth(); // Muncul jika belum bayar bulan ini
                }

                return true;
            });

            $view->with('tagihanGlobal', $tagihanGlobal);
        });
    }
}