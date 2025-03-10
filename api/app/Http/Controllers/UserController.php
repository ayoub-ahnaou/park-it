<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['users' => User::all()], 200);
    }

    public function pastReservations(User $user)
    {
        $reservations = $user->reservations()
            ->where("end_date", "<=", now())
            ->get();
        return response()->json(["count" => sizeof($reservations), "reservations" => $reservations]);
    }

    public function currentReservations(User $user)
    {
        $reservations = $user->reservations()
            ->where("end_date", ">=", now())
            ->get();
        return response()->json(["count" => sizeof($reservations), "reservations" => $reservations]);
    }
}
