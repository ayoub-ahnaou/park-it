<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

/**
 * @OA\Schema(
 *     schema="Reservation",
 *     title="Reservation",
 *     description="Reservation model representing a reservation in the system",
 *     @OA\Property(property="id", type="integer", description="Unique identifier for the reservation"),
 *     @OA\Property(property="user_id", type="integer", description="ID of the user who made the reservation"),
 *     @OA\Property(property="parking_id", type="integer", description="ID of the parking associated with the reservation"),
 *     @OA\Property(property="start_date", type="string", format="date-time", description="Start date and time of the reservation"),
 *     @OA\Property(property="end_date", type="string", format="date-time", description="End date and time of the reservation"),
 *     @OA\Property(property="status", type="string", description="Status of the reservation (e.g., active, canceled)"),
 *     @OA\Property(property="price_total", type="number", format="float", description="Total price of the reservation"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the reservation was created"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the reservation was last updated")
 * )
 */
class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        "start_date",
        "end_date",
        "user_id",
        "parking_id",
        "price_total",
        "status"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parking()
    {
        return $this->belongsTo(Parking::class);
    }
}
