<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Offer;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function store(Request $request,Offer $offer){
        $user = $request->user();

        $request->validate([
            'cv_url' => 'required|url'
        ]);

        $application = Application::create([
            'cv_url' => $request->cv_url,
            'user_id' => $user->id,
            'offer_id' => $offer->id
        ]);

        return response()->json([
            'message' => 'Application submitted successfully',
            'application' => $application
        ], 201);
    }
}
