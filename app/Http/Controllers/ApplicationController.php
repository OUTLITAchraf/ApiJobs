<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Offer;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{

    /**
     * @OA\Get(
     *     path="/my-applications",
     *     tags={"Application"},
     *     summary="Get applications of auth user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=201,
     *         description="Applications Fetched Successfully",
     *         @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="Applications Fetched Successfully"),
     *             @OA\Property(property="applications", type="object")
     *         ) 
     *     ),
     * )
     */
    public function myapplications(Request $request)
    {

        $user = $request->user();
        $user->load('roles');

        if ($user->roles->contains('name', 'user')) {
            $applications = Application::where('user_id', $user->id)->with('offer')->get();
        }

        return response()->json([
            "message" => "Applications Fetched Successfully",
            "applications" => $applications
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/offer/{id}/apply",
     *     tags={"Application"},
     *     summary="Apply in an offer",
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
     *             required={"cv_url"},
     *             @OA\Property(property="cv_url", type="string", example="https://example.com/cv.pdf")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Application submitted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Application submitted successfully"),
     *             @OA\Property(property="application", type="object"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request, Offer $offer)
    {
        $user = $request->user();

        $request->validate([
            'cv_url' => 'required|url'
        ]);

        $applications = Application::all();

        if ($applications->where('user_id','==',$user->id)->where('offer_id','==',$offer->id)->first()) {
            return response()->json([
                "message" => "You already apply in this offer!!"
            ], 422);
        } else {
            $application = Application::create([
                'cv_url' => $request->cv_url,
                'user_id' => $user->id,
                'offer_id' => $offer->id
            ]);
            $application->load('user', 'offer');
        }

        return response()->json([
            'message' => 'Application submitted successfully',
            'application' => $application
        ], 201);
    }


    /**
     * @OA\Put(
     *     path="/update-application/{id}",
     *     tags={"Application"},
     *     summary="Update status application",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Application ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *                 @OA\Property(property="status", type="string", example="accepted or rejected"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Status Application Updated Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Status Application Updated Successfully"),
     *             @OA\Property(property="application", type="object"),            
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Application not found"
     *     ),
     * )
     */
    public function update(Request $request, Application $application)
    {

        $validated = $request->validate([
            "status" => "required|string|in:accepted,rejected"
        ]);

        $application->update($validated);
        $application->load('offer', 'user');

        return response()->json([
            "message" => "Status Application Updated Successfully",
            "application" => $application
        ], 201);
    }
}
