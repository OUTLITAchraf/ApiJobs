<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{

    /**
     * @OA\GET(
     *     path="/offers",
     *     summary="Get offers",
     *     tags={"Offer"},
     *     security={{"bearerAuth":{}}},    
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Offers Fetched Successfully"),
     *             @OA\Property(property="offers", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Offre Title"),
     *                     @OA\Property(property="description", type="string", example="Description of offer"),
     *                     @OA\Property(property="company_name", type="string", example="Name Company"),
     *                     @OA\Property(property="location", type="string", example="Location of Company"),
     *                     @OA\Property(property="salary", type="string", example="1000.00"),
     *                     @OA\Property(property="type", type="string", example="Type of offer"),
     *                     @OA\Property(property="employer_id", type="integer", example=1)
     *                 )
     *             ),
     *         )
     *     ),
     *      
     * )
     */
    public function index()
    {
        $offers = Offer::all();
        return response()->json([
            "message" => "Offers Fetched Successfully",
            "offers" => $offers
        ], 201);
    }


    /**
     * @OA\Get(
     *     path="/offer/{id}",
     *     tags={"Offer"},
     *     summary="Get detail an offer",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Offer ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Offer Fetched Successfully",
     *         @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="Offer Fetched Successfully"),
     *             @OA\Property(property="offer", type="object")
     *         ) 
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Offer not found"
     *     ),
     * )
     */

    public function show($id)
    {
        $offer = Offer::findOrFail($id);
        $offer->load('user');

        return response()->json([
            "message" => "Offer Fetched Successfully",
            "offer" => $offer
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/create-offer",
     *     tags={"Offer"},
     *     summary="Create a new offer",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description","company_name","location","type"},
     *             @OA\Property(property="title", type="string", example="New Offer"),
     *             @OA\Property(property="description", type="string", example="Text Description"),
     *             @OA\Property(property="company_name", type="string", example="Company Name"),
     *             @OA\Property(property="location", type="string", example="Company Location"),
     *             @OA\Property(property="salary", type="string", example="5000.00"),
     *             @OA\Property(property="type", type="string", example="job")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Offer created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Offer Created Successfully"),
     *             @OA\Property(property="offer", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="New Offer"),
     *                 @OA\Property(property="description", type="string", example="Text Description"),
     *                 @OA\Property(property="company_name", type="string", example="Company Name"),
     *                 @OA\Property(property="location", type="string", example="Company Location"),
     *                 @OA\Property(property="salary", type="string", example="5000.00"),
     *                 @OA\Property(property="type", type="string", example="job")
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */

    public function store(Request $request)
    {

        $user = auth()->user();

        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'company_name' => 'required|string',
            'location' => 'required|string',
            'salary' => 'nullable|string',
            'type' => 'required|in:internship,job'
        ]);

        $offer = Offer::create([
            'title' => $request->title,
            'description' => $request->description,
            'company_name' => $request->company_name,
            'location' => $request->location,
            'salary' => $request->salary,
            'type' => $request->type,
            'employer_id' => $user->id
        ]);

        return response()->json([
            "message" => "Offer Created Successfully",
            "offer" => $offer
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/update-offer/{id}",
     *     tags={"Offer"},
     *     summary="Update existing offer",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Offer ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description","company_name","location","type"},
     *                 @OA\Property(property="title", type="string", example="Update Offer"),
     *                 @OA\Property(property="description", type="string", example="New Text Description"),
     *                 @OA\Property(property="company_name", type="string", example="New Company Name"),
     *                 @OA\Property(property="location", type="string", example="New Company Location"),
     *                 @OA\Property(property="salary", type="string", example="1000.00"),
     *                 @OA\Property(property="type", type="string", example="internship")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Offer updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Offer Updated Successfully"),
     *             @OA\Property(property="offer", type="object"),            
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Offer not found"
     *     ),
     * )
     */

    public function update(Request $request, $id)
    {

        $offer = Offer::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'company_name' => 'required|string',
            'location' => 'required|string',
            'salary' => 'nullable|string',
            'type' => 'required|in:internship,job'
        ]);

        $offer->update($validated);

        return response()->json([
            "message" => "Offer Updated Successfully",
            "offer" => $offer
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/delete-offer/{id}",
     *     tags={"Offer"},
     *     summary="Delete an offer",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Offer ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Offer deleted successfully",
     *         @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="Offer Deleted Successfully"),
     *             @OA\Property(property="offer", type="object")
     *        )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Offer not found"
     *     )
     * )
     */

    public function destroy($id)
    {

        $offer = Offer::findOrFail($id);
        $offer->delete();

        return response()->json([
            "message" => "Offer Deleted Successfully",
            "offer" => $offer
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/offers/search",
     *     tags={"Offer"},
     *     summary="Search offers by title or company name or location",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Search by offer title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="company_name",
     *         in="query",
     *         description="Search by company name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="location",
     *         in="query",
     *         description="Search by location",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Title Offer"),
     *                     @OA\Property(property="description", type="string", example="Description of offer"),
     *                     @OA\Property(property="company_name", type="string", example="Company Name"),
     *                     @OA\Property(property="location", type="string", example="Company Location"),
     *                     @OA\Property(property="salary", type="string", example="1000.00"),
     *                     @OA\Property(property="type", type="string", example="Type of offer")
     *                 )
     *             ),
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="per_page", type="integer", example=10)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No offers found matching your search."
     *     )
     * )
     */

    public function search(Request $request)
    {
        $query = Offer::query();

        if ($request->has('title')) {
            $query->where('title', 'LIKE', '%' . $request->title . '%');
        }

        if ($request->has('company_name')) {
            $query->where('company_name', 'LIKE', '%' . $request->company_name . '%');
        }

        if ($request->has('location')) {
            $query->where('location', 'LIKE', '%' . $request->location . '%');
        }

        $offers = $query->get();

        if ($offers->isEmpty()) {
            return response()->json([
                "message" => "No offers found matching your search."
            ], 404);
        }

        return response()->json([
            "message" => "Offers Fetched Successfully",
            "offers" => $offers
        ], 201);
    }
}
