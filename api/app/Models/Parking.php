<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

/**
 * @OA\Schema(
 *     schema="Parking",
 *     title="Parking",
 *     description="Parking model representing a parking location in the system",
 *     @OA\Property(property="id", type="integer", description="Unique identifier for the parking"),
 *     @OA\Property(property="name", type="string", maxLength=255, description="Name of the parking"),
 *     @OA\Property(property="city", type="string", description="City where the parking is located"),
 *     @OA\Property(property="zone", type="string", description="Zone or area of the parking"),
 *     @OA\Property(property="places", type="integer", description="Total number of parking places"),
 *     @OA\Property(property="places_disponible", type="integer", nullable=true, description="Number of available parking places"),
 *     @OA\Property(property="price", type="number", format="float", description="Price per parking slot"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the parking was created"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the parking was last updated")
 * )
 */
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
