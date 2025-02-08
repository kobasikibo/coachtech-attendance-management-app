<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status', 'clock_in', 'clock_out', 'break_start', 'break_end'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
