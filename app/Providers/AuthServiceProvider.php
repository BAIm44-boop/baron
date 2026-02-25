<?php

namespace App\Providers;
use App\Models\Produk;
use App\Models\Pembelian;
use App\Policies\ProdukPolicy;
use App\Policies\PembelianPolicy;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }
protected $policies = [
Produk::class => ProdukPolicy::class,
Pembelian::class => PembelianPolicy::class,
];
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
