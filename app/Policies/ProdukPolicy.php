<?php
namespace App\Policies;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
class ProdukPolicy
{
    /**
     * Menampilkan / Menghilangkan Tampilan Menu Produks
     */
    public function viewAny(User $user): bool
    {
        return Auth::user()->role === 'Admin';
    }

    /**
     * Menampilkan / Menghilangkan Tampilan Tabel Produks
     */
    public function view(User $user, Produk $produk): bool
    {
        return false;
    }

    /**
     * Menampilkan / Menghilangkan Input Produks
     */
    public function create(User $user): bool
    {
        return Auth::user()->role === 'Admin';
    }

    /**
     * Menampilkan / Menghilangkan Edit Produks
     */
    public function update(User $user, Produk $produk): bool
    {
        return Auth::user()->role === 'Admin';
    }

    /**
     * Menampilkan / Menghilangkan Delete Produks
     */
    public function delete(User $user, Produk $produk): bool
    {
        return Auth::user()->role === 'Admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Produk $produk): bool
    {
        return false;
    }
    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Produk $produk): bool
    {
        return false;
    }
}
