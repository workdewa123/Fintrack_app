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
        // Cek apakah ada user yang sedang login
        if (Auth::check()) {
            // Ambil pengingat milik user ini saja
            $tagihanGlobal = Pengingat::where('id_pengguna', Auth::id())
                ->get()
                ->filter(function ($item) {
                    if (is_null($item->tanggal_bayar_terakhir)) {
                        return true;
                    }

                    $terakhirBayar = Carbon::parse($item->tanggal_bayar_terakhir);

                    // Logika frekuensi (tetap sama)
                    if ($item->frekuensi === 'HARIAN') {
                        return !$terakhirBayar->isToday();
                    }
                    if ($item->frekuensi === 'MINGGUAN') {
                        return !$terakhirBayar->isCurrentWeek();
                    }
                    if ($item->frekuensi === 'BULANAN') {
                        return !$terakhirBayar->isCurrentMonth();
                    }

                    return true;
                });

            $view->with('tagihanGlobal', $tagihanGlobal);
        } else {
            // Kirim koleksi kosong jika belum login
            $view->with('tagihanGlobal', collect());
        }
    });
}
}