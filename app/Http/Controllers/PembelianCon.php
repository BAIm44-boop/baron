<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class PembelianCon extends Controller
{
    public function storeinput(Request $request)
    {
        // insert data ke table tbpembelian
        $data = DB::table('pembelians')->count();
        $result = $data + 1;
        DB::table('pembelians')->insert(['kode_pembelian' =>
                "P-" . $request->kodeproduk . "-" . Auth::user()->id . $result,
            'produk_id' => $request->kodeproduk,
            'banyak' => $request->banyak,
            'bayar' => $request->harga * $request->banyak,
            'user_id' => Auth::user()->id,
            'status' => 'Verifikasi'
        ]);
        // alihkan halaman ke route pembelian
        return redirect('/admin/pembelians');
    }
}
