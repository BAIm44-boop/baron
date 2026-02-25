<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Pembelian extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_pembelian',
        'produk_id',
        'banyak',
        'bayar',
        'user_id',
        'status'
    ];
    public function produk()
    {
        return $this->belongsTo(produk::class);
    }
    public function user()
    {
        return $this->belongsTo(user::class);
    }
}
