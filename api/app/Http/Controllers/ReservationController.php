<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::all();
        return response()->json(['count' => sizeof($reservations), 'reservations' => $reservations], 200);
    }

    public function store(Request $request, Parking $parking)
    {
        // 
    }

    public function show(Reservation $reservation)
    {
        // 
    }

    public function update(Request $request, Reservation $reservation)
    {
        // 
    }

    public function destroy(Reservation $reservation)
    {
        // 
    }
}
