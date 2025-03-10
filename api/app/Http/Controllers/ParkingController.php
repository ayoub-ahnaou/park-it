<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parkings = Parking::all();
        return response()->json(['parkings' => $parkings], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            "name" => ['required'],
            "city" => ['required'],
            "zone" => ['required'],
            "places" => ['required', 'integer'],
            "price" => ['required', 'numeric', 'min:10']
        ]);

        $parking = Parking::create($fields);
        return response()->json(['parking' => $parking], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Parking $parking)
    {
        return response()->json(['parking' => $parking]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Parking $parking)
    {
        // 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parking $parking)
    {
        // 
    }
}
