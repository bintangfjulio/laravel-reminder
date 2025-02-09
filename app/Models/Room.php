<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';

    protected $fillable = ['name'];

    public function lends()
    {
        return $this->hasMany(Lend::class);
    }
}
