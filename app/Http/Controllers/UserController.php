<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @OA\GET(
     *     path="/admin/users",
     *     summary="Get users list",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},    
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Users Retrieved Successfully"),
     *             @OA\Property(property="users", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="User Name"),
     *                     @OA\Property(property="email", type="string", example="User Email"),
     *                 )
     *             ),
     *         )
     *     ),
     *      
     * )
     */ 
    public function index(){
        $users = User::where('name','!=','Admin')->get();
        return response()->json([
            "message" => "Users Retrieved Successfully",
            "users" => $users,
        ], 201);
    }


    /**
     * @OA\Get(
     *     path="/admin/user/{id}",
     *     tags={"User"},
     *     summary="Get detail an user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User Fetched Successfully",
     *         @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="User Fetched Successfully"),
     *             @OA\Property(property="user", type="object")
     *         ) 
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     * )
     */
    public function show($id){
        $user = User::findOrFail($id);
        $user->load('applications.offer');
        return response()->json([
            "message" => "User Retrieved Successfully",
            "user" => $user
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/admin/update-user/{id}",
     *     tags={"User"},
     *     summary="Update existing user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *                 @OA\Property(property="name", type="string", example="New name"),
     *                 @OA\Property(property="email", type="string", example="new@exemple.com"),
     *                 @OA\Property(property="password", type="string", example="New password"),
     *                 @OA\Property(property="password_confirmation", type="string", example="Password confirmation"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User Updated Successfully"),
     *             @OA\Property(property="user", type="object"),            
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     * )
     */
    public function update(Request $request,User $user){
        $validated = $request->validate([
            "name" => "sometimes|string",
            "email" => "sometimes|email|unique:users,email",
            "password" => "sometimes|string|confirmed"
        ]);

        $user->update($validated);

        return response()->json([
            "message" => "User updated successfully",
            "user" => $user,
        ], 201);
    }


    /**
     * @OA\Delete(
     *     path="/admin/delete-user/{id}",
     *     tags={"User"},
     *     summary="Delete an user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User deleted successfully",
     *         @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="User Deleted Successfully"),
     *             @OA\Property(property="user", type="object")
     *        )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
     */
    public function destroy(Request $request,User $user){
        $user->delete();

        return response()->json([
            "message" => "User deleted successfully",
            "user" => $user
        ], 201);
    }
}
