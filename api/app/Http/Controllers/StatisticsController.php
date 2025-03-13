<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/admin/statistics/overview",
     *     tags={"Statistics"},
     *     description="Get an overview of system statistics",
     *     @OA\Response(
     *         response=200,
     *         description="Overview statistics retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="total_users",
     *                 type="integer",
     *                 description="Total number of users"
     *             ),
     *             @OA\Property(
     *                 property="total_parkings",
     *                 type="integer",
     *                 description="Total number of parkings"
     *             ),
     *             @OA\Property(
     *                 property="total_reservations",
     *                 type="integer",
     *                 description="Total number of reservations"
     *             ),
     *             @OA\Property(
     *                 property="revenue",
     *                 type="number",
     *                 format="float",
     *                 description="Total revenue generated"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function overview()
    {
        $totalUsers = User::count();
        $totalParkings = Parking::count();
        $totalReservations = Reservation::count();
        $revenue = Reservation::sum("price_total");

        return response()->json([
            "total_users" => $totalUsers,
            "total_parkings" => $totalParkings,
            "total_reservations" => $totalReservations,
            "revenue" => $revenue,
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/admin/statistics/users",
     *     tags={"Statistics"},
     *     description="Get all users",
     *     @OA\Response(
     *         response=200,
     *         description="Users retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="count",
     *                 type="integer",
     *                 description="Total number of users"
     *             ),
     *             @OA\Property(
     *                 property="users",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/User")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function users()
    {
        $newUserThisDay = User::where("created_at", ">=", now()->startOfDay())->count();
        return response()->json(["new_users_this_day" => $newUserThisDay, "users" => User::all()], 200);
    }

    /**
     * @OA\Get(
     *     path="/admin/statistics/parkings",
     *     tags={"Statistics"},
     *     description="Get all parking data",
     *     @OA\Response(
     *         response=200,
     *         description="Parking data retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="parkings",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Parking")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function parkings()
    {
        return response()->json(["parkings" => Parking::all()], 200);
    }

    /**
     * @OA\Get(
     *     path="/admin/statistics/reservations",
     *     tags={"Statistics"},
     *     description="Get all reservation data",
     *     @OA\Response(
     *         response=200,
     *         description="Reservation data retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
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
    public function reservations()
    {
        return response()->json(["reservations" => Reservation::all()], 200);
    }

    /**
     * @OA\Get(
     *     path="/admin/statistics/revenue",
     *     tags={"Statistics"},
     *     description="Get revenue-related statistics",
     *     @OA\Response(
     *         response=200,
     *         description="Revenue statistics retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="total_revenue",
     *                 type="number",
     *                 format="float",
     *                 description="Total revenue generated"
     *             ),
     *             @OA\Property(
     *                 property="revenue_this_month",
     *                 type="number",
     *                 format="float",
     *                 description="Revenue generated this month"
     *             ),
     *             @OA\Property(
     *                 property="revenue_by_parking",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="parking_id",
     *                         type="integer",
     *                         description="ID of the parking"
     *                     ),
     *                     @OA\Property(
     *                         property="amount",
     *                         type="number",
     *                         format="float",
     *                         description="Total revenue for this parking"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function revenue()
    {
        $totalRevenue = Reservation::sum("price_total");
        $revenueThisMonth = Reservation::where("created_at", ">=", now()->startOfMonth())->sum("price_total");
        $revenueByParking = Reservation::select("parking_id", DB::raw("SUM(price_total) as amount"))
            ->groupBy("parking_id")
            ->get();

        return response()->json([
            "total_revenue" => $totalRevenue,
            "revenue_this_month" => $revenueThisMonth,
            "revenue_by_parking" => $revenueByParking
        ], 200);
    }
}
