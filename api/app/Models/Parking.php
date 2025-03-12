<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    protected $fillable = [
        "name",
        "city",
        "zone",
        "places",
        "price"
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
