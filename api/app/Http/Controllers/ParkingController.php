<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Parking::query();

        if ($request->has('query')) {
            $search = strtolower($request->input("query"));
            $query->whereRaw("LOWER(name) LIKE ?", ["%$search%"])
                ->orWhereRaw("LOWER(city) LIKE ?", ["%$search%"])
                ->orWhereRaw("LOWER(zone) LIKE ?", ["%$search%"])
                ->orWhere("places", intval($search));
        }

        $parkings = $query->where("places_disponible", ">", 0)
            ->Where("places_disponible", "!=", null)
            ->get();
        return response()->json(["count" => sizeof($parkings), "parkings" => $parkings], 200);
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
        $fields = $request->validate([
            "name" => ['required'],
            "city" => ['required'],
            "zone" => ['required'],
            "places" => ['required', 'integer'],
            "price" => ['required', 'numeric', 'min:10']
        ]);

        $parking->update($fields);
        return response()->json(['parking' => $parking], 203);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parking $parking)
    {
        $parking->delete();
        return response()->json(['message' => 'parking deleted succefully']);
    }
}
