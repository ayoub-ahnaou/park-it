<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/user",
     *     tags={"User"},
     *     description="Get the details of the authenticated user",
     *     @OA\Response(
     *         response=200,
     *         description="User displayed successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="user",
     *                 ref="#/components/schemas/User"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    /**
     * @OA\Get(
     *     path="/user/past-reservations",
     *     tags={"User"},
     *     description="Get past reservations of the authenticated user",
     *     @OA\Response(
     *         response=200,
     *         description="Past reservations retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="count",
     *                 type="integer",
     *                 description="Number of past reservations"
     *             ),
     *             @OA\Property(
     *                 property="reservations",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Reservation")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function pastReservations(User $user)
    {
        $reservations = $user->reservations()
            ->where("end_date", "<=", now())
            ->get();
        return response()->json(["count" => sizeof($reservations), "reservations" => $reservations]);
    }

    /**
     * @OA\Get(
     *     path="/user/current-reservations",
     *     tags={"User"},
     *     description="Get current reservations of the authenticated user",
     *     @OA\Response(
     *         response=200,
     *         description="Current reservations retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="count",
     *                 type="integer",
     *                 description="Number of current reservations"
     *             ),
     *             @OA\Property(
     *                 property="reservations",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Reservation")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function currentReservations(User $user)
    {
        $reservations = $user->reservations()
            ->where("end_date", ">=", now())
            ->get();
        return response()->json(["count" => sizeof($reservations), "reservations" => $reservations]);
    }
}
