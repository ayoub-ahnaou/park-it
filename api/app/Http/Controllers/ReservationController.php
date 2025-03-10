<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return response()->json(["reservation" => $reservation]);
    }

    public function update(Request $request, Reservation $reservation)
    {
        // 
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return response()->json(['message' => "reservation deleted with succes"]);
    }
}
