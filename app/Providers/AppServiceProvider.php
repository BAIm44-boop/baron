<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
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
        Filament::serving(function () {
            Filament::registerNavigationItems([
                NavigationItem::make('Halaman Utama')->
                    url(route('homeproduk'))
                    ->icon('heroicon-o-link')//Ikon untuk menu
                    ->sort(100), // Urutan menu di sidebar
            ]);
        });

        Filament::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn(): HtmlString => new HtmlString(
                '<div class="mt-6 text-center">
           <a href="/" class="text-sm text-gray-400 hover:text-primary-500 transition">
            <- Kembali ke Halaman Utama
            </a>
            </div>'
            )
        );
    }


}
