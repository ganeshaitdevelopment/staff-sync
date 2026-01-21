<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = [];

    // Konversi JSON di database jadi Array PHP otomatis
    protected $casts = [
        'allowed_roles' => 'array',
    ];

    // Relasi ke "Anak-anaknya" (Sub Menu)
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    // Relasi ke "Bapaknya" (Parent Menu)
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }
}
