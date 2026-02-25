<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
class RegisterCon extends Controller
{
public function register()
{
return view('register');
}
public function actionregister(Request $request)
{
DB::table('users')->insert([
'name' => $request->username,
'email' => $request->email,
'password' => bcrypt($request->password),
'role' => $request->role,
'created_at' => date('Y-m-d H:i:s')
]);
Session::flash('message', 'Register Berhasil. Akun Anda
sudah Aktif silahkan Login
menggunakan username dan password.');
return redirect('register');
}
}
