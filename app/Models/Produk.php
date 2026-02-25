<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Produk extends Model
{
use HasFactory;
protected $fillable = ['kode', 'nama', 'tipe', 'jenis',
'harga', 'stok', 'image'];
public function pembelian()
{
return $this->hasMany(pembelian::class);
}
}
