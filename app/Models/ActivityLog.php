<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    // IZINKAN SEMUA KOLOM DIISI
    protected $guarded = []; 

    // Relasi ke User (Supaya bisa tampil nama pelakunya di tabel log nanti)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}