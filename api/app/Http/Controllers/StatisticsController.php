<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
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

    public function users()
    {
        $newUserThisDay = User::where("created_at", ">=", now()->startOfDay())->count();
        return response()->json(["new_users_this_day" => $newUserThisDay, "users" => User::all()], 200);
    }

    public function parkings()
    {
        return response()->json(["parkings" => Parking::all()], 200);
    }

    public function reservations()
    {
        return response()->json(["reservations" => Reservation::all()], 200);
    }

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
