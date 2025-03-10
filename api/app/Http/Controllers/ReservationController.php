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
        // TODO still need to implement the deletion of reservations expired to give chance to others to reserve a place
        // $isStillAvailablePlaces = Reservation::where('parking_id', $parking->id)->count();
        if ($parking->places_disponible == 0) return response()->json(["message" => "Places reached the maximum number in this parking"]);

        $request->validate([
            'start_date' => 'required|date_format:Y-m-d H:i:s|after:now',
            'end_date'   => 'required|date_format:Y-m-d H:i:s|after:start_date',
        ]);

        // check if a reservation is made with the datetime giving bu user
        // start date: 2025-03-11 20:00:00 & end date: 2025-03-11 23:00:00
        $reservation = Reservation::where("start_date", ">=", $request->start_date)
            ->where("end_date", "<=", $request->end_date)
            ->where("parking_id", $parking->id)->first();

        if ($reservation) {
            if ($reservation->end_date <= $request->end_date)
                return response()->json(["message" => "Choose a date start from {$reservation->end_date} or above"]);
            else
                return response()->json(["message" => "Sorry, parking already taken between this time"]);
        }

        $hours = Carbon::parse($request->start_date)->diffInHours(Carbon::parse($request->end_date));

        if ($hours < 1) return response()->json(["message" => "minimum duration required is an hour"]);

        $reservation = Reservation::create([
            'user_id' => Auth::user()->id,
            'parking_id' => $parking->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'price_total' => $parking->price * $hours,
        ]);

        $parking->places_disponible = ($parking->places - Reservation::where("parking_id", $parking->id)->count());
        $parking->save();

        return response()->json(["message" => "reservation created with succes", "reservation" => $reservation], 201);
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
