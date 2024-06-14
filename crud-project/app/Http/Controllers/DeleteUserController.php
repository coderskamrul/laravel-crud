<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_crud;
use Illuminate\Http\JsonResponse;

class DeleteUserController extends Controller
{
    protected $users;

    public function __construct(){
        $this->users = new User_crud();
    }
   
    public function destroy(Request $request, string $id): JsonResponse
    {
        // Find the user
        $delete_user = $this->users->find($id);
        
        // Check if user exists
        if (!$delete_user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // Delete user
        $delete_user->delete();

        // Return success message
        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }
}
