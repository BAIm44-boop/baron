<?php
namespace App\Policies;
use App\Models\Pembelian;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
class PembelianPolicy
{
    /**
     * Menampilkan / Menghilangkan Tampilan Menu Pembelian
     */
    public function viewAny(User $user): bool
    {
        return true;
    }
    /**
     * Menampilkan / Menghilangkan Tampilan Tabel Pembelian
     */
    public function view(User $user, Pembelian $pembelian): bool
    {
        return false;
    }

    /**
     * Menampilkan / Menghilangkan Input Pembelian
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Menampilkan / Menghilangkan Edit Pembelian
     */
    public function update(User $user, Pembelian $pembelian): bool
    {
        return Auth::user()->role === 'Admin';
    }

    /**
     * Menampilkan / Menghilangkan Delete Pembelian
     */
    public function delete(User $user, Pembelian $pembelian): bool
    {
        return Auth::user()->role === 'Admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pembelian $pembelian): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(
        User $user,
        Pembelian $pembelian
    ): bool {
        return false;
    }
}
