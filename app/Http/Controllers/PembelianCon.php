<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class PembelianCon extends Controller
{
    public function storeinput(Request $request)
    {
        // Validasi input minimal
        $validated = $request->validate([
            'kodeproduk' => 'required|integer|exists:produks,id',
            'banyak' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'nama_user' => 'nullable|string|max:255', // untuk guest/user menulis nama
        ]);

        // Transaksi agar stok konsisten dan mencegah overselling
        DB::beginTransaction();
        try {
            // Lock row produk untuk update stok aman
            $produk = DB::table('produks')->where('id', $validated['kodeproduk'])->lockForUpdate()->first();

            if (!$produk) {
                abort(404, 'Produk tidak ditemukan');
            }

            // Cek stok cukup
            if ((int) $produk->stok < (int) $validated['banyak']) {
                DB::rollBack();
                return back()->withErrors(['banyak' => 'Stok tidak mencukupi. Sisa stok: ' . (int) $produk->stok]);
            }

            $data = DB::table('pembelians')->count();
            $result = $data + 1;

            $userId = Auth::check() ? Auth::user()->id : null;
            $userName = Auth::check() ? Auth::user()->name : ($validated['nama_user'] ?? 'Guest');

            // Simpan pembelian dan tetap rekam relasi user_id untuk konsistensi data
            DB::table('pembelians')->insert([
                'kode_pembelian' => "P-" . $validated['kodeproduk'] . "-" . ($userName) . $result,
                'produk_id' => $validated['kodeproduk'],
                'banyak' => $validated['banyak'],
                'bayar' => $validated['harga'] * $validated['banyak'],
                'user_id' => $userId,
                'status' => 'Verifikasi',
            ]);

            // Kurangi stok produk sesuai banyak yang dibeli
            DB::table('produks')->where('id', $validated['kodeproduk'])->update([
                'stok' => (int) $produk->stok - (int) $validated['banyak'],
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Gagal membuat pembelian: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memproses pembelian.']);
        }

        // alihkan halaman ke route pembelian
        return redirect('/admin/pembelians');
    }
}
