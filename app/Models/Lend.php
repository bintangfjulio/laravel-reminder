<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lend extends Model
{
    protected $table = 'lends';
    protected $fillable = ['user_id', 'room_id', 'lend_start', 'lend_end', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    const STATUSES = [
        1 => 'Waiting',
        2 => 'Booked',
        3 => 'Rejected',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? '-';
    }
}
