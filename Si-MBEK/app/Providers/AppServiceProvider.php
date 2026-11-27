<?php

namespace App\Providers;

use App\Models\Penyakit;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        // Notifikasi kandang isolasi di layout
        View::composer(['layouts.app', 'layouts.navbar', 'dashboard.index', 'kandang.index'], function ($view) {
            $aktif = self::kasusAktif();

            $view->with([
                'sakitIsolasi'      => $aktif->filter->diIsolasi()->values(),
                'sakitBelumIsolasi' => $aktif->reject->diIsolasi()->values(),
            ]);
        });

        // Badge kambing marker sakit di tabel
        View::composer('kesehatan.partials.kambing-badge', function ($view) {
            $view->with('idKambingSakit', self::kasusAktif()->pluck('id_kambing')->unique()->all());
        });
    }

    /**
     * Daftar kasus penyakit aktif
     */
    public static function kasusAktif()
    {
        $req = request();

        if (! $req->attributes->has('kesehatan.kasus_aktif')) {
            $req->attributes->set('kesehatan.kasus_aktif', Schema::hasTable('penyakit')
                ? Penyakit::aktif()->with('kambing.kandang')->orderBy('tanggal_sakit')->get()
                : collect());
        }

        return $req->attributes->get('kesehatan.kasus_aktif');
    }
}
