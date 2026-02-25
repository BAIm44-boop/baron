<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukCon;
use App\Http\Controllers\PembelianCon;
use App\Http\Controllers\RegisterCon;
use Illuminate\Support\Facades\Auth;
Route::get('/', [ProdukCon::class, 'home'])->name('homeproduk');
Route::post('/pembelian/storeinput', [PembelianCon::class,
'storeinput'])->name('storeInputpembelian')->middleware('auth');
Route::get('/register', [RegisterCon::class,
'register'])->name('register');
Route::post('/register/action', [RegisterCon::class,
'actionregister'])->name('actionregister');
Route::post('/logout', function () {
Auth::logout();
return redirect('/');
})->name('logout'); 
