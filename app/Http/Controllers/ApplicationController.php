<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Offer;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{

        public function myapplications(Request $request){

        $user = $request->user();
        $user->load('roles');

        if ($user->roles->contains('name', 'user')){
            $applications = Application::where('user_id',$user->id)->with('offer')->get();
        }

        return response()->json([
            "message" => "Applications Fetched Successfully",
            "applications" => $applications
        ], 201);
    }

    
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
