<?php

namespace App\Filament\Resources\Pembelians\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PembelianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_pembelian')
                ->hidden()
                    ->required(),
                TextInput::make('produk_id')
                    ->required()
                    ->hidden()
                    ->numeric(),
                TextInput::make('banyak')
                    ->required()
                    ->hidden()
                    ->numeric(),
                TextInput::make('bayar')
                    ->required()
                    ->hidden()
                    ->numeric(),
                TextInput::make('user_nama')
                    ->required()
                    ->hidden()
                    ->numeric(),
                Select::make('status')
                    ->options([
            'Verifikasi' => 'Verifikasi',
            'Proses' => 'Proses',
            'Kirim' => 'Kirim',
            'Sampai' => 'Sampai',
            'Selesai' => 'Selesai',
        ])
                    ->required(),
            ]);
    }
}
