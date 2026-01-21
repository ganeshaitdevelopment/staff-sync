<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function getDurationAttribute()
    {
        return \Carbon\Carbon::parse($this->start_date)
            ->diffInDays(\Carbon\Carbon::parse($this->end_date)) + 1;
    }
}
