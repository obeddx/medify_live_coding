<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class MasterItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode', 'nama', 'harga_beli', 'laba', 'supplier', 'jenis', 'foto',
    ];

    public function kategoris()
    {
        return $this->belongsToMany(Kategori::class, 'kategori_master_item');
    }

    // $item->harga_jual  (laba dalam persen)
    public function getHargaJualAttribute()
    {
        return (int) round($this->harga_beli + ($this->harga_beli * $this->laba / 100));
    }

    // $item->foto_url
    public function getFotoUrlAttribute()
    {
        return $this->foto ? Storage::url($this->foto) : null;
    }
}