<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/reservations/{parking}",
     *     tags={"Reservation"},
     *     description="Create a new reservation for a parking",
     *     @OA\Parameter(
     *         name="parking",
     *         in="path",
     *         required=true,
     *         description="ID of the parking",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"start_date", "end_date"},
     *             @OA\Property(
     *                 property="start_date",
     *                 type="string",
     *                 format="date-time",
     *                 example="2025-03-11 20:00:00",
     *                 description="Start date and time of the reservation"
     *             ),
     *             @OA\Property(
     *                 property="end_date",
     *                 type="string",
     *                 format="date-time",
     *                 example="2025-03-11 23:00:00",
     *                 description="End date and time of the reservation"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reservation created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="reservation created with success"
     *             ),
     *             @OA\Property(
     *                 property="reservations",
     *                 ref="#/components/schemas/Reservation"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request (e.g., invalid date range or parking full)"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function store(Request $request, Parking $parking)
    {
        // TODO still need to implement the deletion of reservations expired to give chance to others to reserve a place
        if ($parking->places_disponible == 0 && $parking->plcaes_disponible != null)
            return response()->json(["message" => "Places reached the maximum number in this parking"]);

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

    /**
     * @OA\Get(
     *     path="/reservations/{reservation}",
     *     tags={"Reservation"},
     *     description="Get details of a specific reservation",
     *     @OA\Parameter(
     *         name="reservation",
     *         in="path",
     *         required=true,
     *         description="ID of the reservation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reservation details retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="reservations",
     *                 ref="#/components/schemas/Reservation"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reservation not found"
     *     )
     * )
     */
    public function show(Reservation $reservation)
    {
        return response()->json(["reservation" => $reservation]);
    }

    /**
     * @OA\Put(
     *     path="/reservations/{reservation}",
     *     tags={"Reservation"},
     *     description="Update a specific reservation",
     *     @OA\Parameter(
     *         name="reservation",
     *         in="path",
     *         required=true,
     *         description="ID of the reservation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"start_date", "end_date"},
     *             @OA\Property(
     *                 property="start_date",
     *                 type="string",
     *                 format="date-time",
     *                 example="2025-03-11 20:00:00",
     *                 description="Start date and time of the reservation"
     *             ),
     *             @OA\Property(
     *                 property="end_date",
     *                 type="string",
     *                 format="date-time",
     *                 example="2025-03-11 23:00:00",
     *                 description="End date and time of the reservation"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reservation updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="reservation updated with success"
     *             ),
     *             @OA\Property(
     *                 property="reservations",
     *                 ref="#/components/schemas/Reservation"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request (e.g., invalid date range)"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reservation not found"
     *     )
     * )
     */
    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'start_date' => 'required|date_format:Y-m-d H:i:s|after:now',
            'end_date'   => 'required|date_format:Y-m-d H:i:s|after:start_date',
        ]);

        // check if a reservation is made with the datetime giving bu user
        // start date: 2025-03-11 20:00:00 & end date: 2025-03-11 23:00:00
        $isReservationExist = Reservation::where("start_date", ">=", $request->start_date)
            ->where("end_date", "<=", $request->end_date)
            ->where("parking_id", $reservation->parking->id)
            ->first();

        if ($isReservationExist) {
            if ($reservation->end_date <= $request->end_date && $isReservationExist->id != $reservation->id)
                return response()->json(["message" => "Choose a date start from {$reservation->end_date} or above"]);
        }

        $hours = Carbon::parse($request->start_date)->diffInHours(Carbon::parse($request->end_date));

        if ($hours < 1) return response()->json(["message" => "minimum duration required is an hour"]);

        $reservation->update([
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'price_total' => $reservation->parking->price * $hours,
        ]);

        return response()->json(["message" => "reservation updated with succes", "reservation" => $reservation], 200);
    }

    /**
     * @OA\Delete(
     *     path="/reservations/{reservation}/cancel",
     *     tags={"Reservation"},
     *     description="Cancel a specific reservation",
     *     @OA\Parameter(
     *         name="reservation",
     *         in="path",
     *         required=true,
     *         description="ID of the reservation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reservation canceled successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="reservation canceled successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reservation not found"
     *     )
     * )
     */
    public function cancel(Reservation $reservation)
    {
        $parking = Parking::find($reservation->parking_id);
        $parking->places_disponible++;
        $parking->save();

        $reservation->delete();

        return response()->json(["message" => "reservation canceld succesfully"]);
    }

    /**
     * @OA\Delete(
     *     path="/reservations/{reservation}",
     *     tags={"Reservation"},
     *     description="Delete a specific reservation",
     *     @OA\Parameter(
     *         name="reservation",
     *         in="path",
     *         required=true,
     *         description="ID of the reservation",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reservation deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="reservation deleted with success"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reservation not found"
     *     )
     * )
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return response()->json(['message' => "reservation deleted with succes"]);
    }
}
