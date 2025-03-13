<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/parkings",
     *     tags={"Parkings"},
     *     description="Get all parkings",
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Search query for filtering parkings by name, city, zone, or places",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Parkings retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="count",
     *                 type="integer",
     *                 description="Total number of parkings"
     *             ),
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
            ->orWhere("places_disponible", null)
            ->get();
        return response()->json(["count" => sizeof($parkings), "parkings" => $parkings], 200);
    }

    /**
     * @OA\Post(
     *     path="/parkings",
     *     tags={"Parkings"},
     *     description="Create a new parking",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "city", "zone", "places", "price"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="Parking A"
     *             ),
     *             @OA\Property(
     *                 property="city",
     *                 type="string",
     *                 example="New York"
     *             ),
     *             @OA\Property(
     *                 property="zone",
     *                 type="string",
     *                 example="Downtown"
     *             ),
     *             @OA\Property(
     *                 property="places",
     *                 type="integer",
     *                 example=100
     *             ),
     *             @OA\Property(
     *                 property="price",
     *                 type="number",
     *                 format="float",
     *                 example=10.5
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Parking created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="parking",
     *                 ref="#/components/schemas/Parking"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
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
     * @OA\Get(
     *     path="/parkings/{parking}",
     *     tags={"Parkings"},
     *     description="Get details of a specific parking",
     *     @OA\Parameter(
     *         name="parking",
     *         in="path",
     *         required=true,
     *         description="ID of the parking",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Parking details retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="parking",
     *                 ref="#/components/schemas/Parking"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Parking not found"
     *     )
     * )
     */
    public function show(Parking $parking)
    {
        return response()->json(['parking' => $parking], 200);
    }

    /**
     * @OA\Put(
     *     path="/parkings/{parking}",
     *     tags={"Parkings"},
     *     description="Update a specific parking",
     *     security={{"bearerAuth": {}}},
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
     *             required={"name", "city", "zone", "places", "price"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="Parking A"
     *             ),
     *             @OA\Property(
     *                 property="city",
     *                 type="string",
     *                 example="New York"
     *             ),
     *             @OA\Property(
     *                 property="zone",
     *                 type="string",
     *                 example="Downtown"
     *             ),
     *             @OA\Property(
     *                 property="places",
     *                 type="integer",
     *                 example=100
     *             ),
     *             @OA\Property(
     *                 property="price",
     *                 type="number",
     *                 format="float",
     *                 example=10.5
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Parking updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="parking",
     *                 ref="#/components/schemas/Parking"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Parking not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
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
        return response()->json(['parking' => $parking], 200);
    }

    /**
     * @OA\Delete(
     *     path="/parkings/{parking}",
     *     tags={"Parkings"},
     *     description="Delete a specific parking",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="parking",
     *         in="path",
     *         required=true,
     *         description="ID of the parking",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Parking deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Parking not found"
     *     )
     * )
     */
    public function destroy(Parking $parking)
    {
        $parking->delete();
        return response()->json(['message' => 'parking deleted succefully'], 204);
    }
}
