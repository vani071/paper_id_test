<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class UserController extends Controller
{
     /**
     * Get all Users Data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers(Request $request){
        return User::select('id','name','email')
                ->whereNull('deleted_at')
                ->orderBy('name')->paginate(10);
    }

     /**
     * Get User detail.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function getUserDetail($id){
        if (User::where('id', $id)->exists()) {
            $user = User::where('id', $id)->get();
            return $user;
          } else {
            return response()->json([
              "message" => "User not found"
            ], 404);
          }
    }

     /**
     * Update User detail.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function updateUsers(Request $request, $id){
        if (User::where('id', $id)->exists()) {
            $user = User::find($id);
    
            $user->name = is_null($request->name) ? $user->name : $request->name;
            $user->email = is_null($request->email) ? $user->email : $request->email;
            $user->password = is_null($request->password) ? $user->password : $request->password;
            $user->save();
    
            return response()->json([
              "message" => "records updated successfully",
              "data" => $user
            ], 200);
          } else {
            return response()->json([
              "message" => "User not found"
            ], 404);
          }
    }

     /**
     * Delete user.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function deleteUser($id){
        if (User::where('id', $id)->exists()) {
            $user = User::find($id);
            $dateTime = new \DateTime('NOW');
            $user->deleted_at = $dateTime->format('Y-m-d H:i:s');
            $user->save();
    
            return response()->json([
              "message" => "records updated successfully",
              "data" => $user
            ], 200);
          } else {
            return response()->json([
              "message" => "User not found"
            ], 404);
          }
    }
}