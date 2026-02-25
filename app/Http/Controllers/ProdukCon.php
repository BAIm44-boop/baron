<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
class ProdukCon extends Controller
{
public function home()
{
$produk = DB::table('produks')->get();
return view('utama', ['produk' => $produk]);
}
}
