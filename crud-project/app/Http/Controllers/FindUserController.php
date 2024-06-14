<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_crud;

class FindUserController extends Controller
{
    protected $users;

    public function __construct()
    {
        $this->users = new User_crud();
    }

    /**
     * Show a newly created resource in storage.
     */
    public function show(Request $request, string $id)
    {
        // Find User
        $user = $this->users->find($id);
        
        // Check if user was found
        if ($user) {
            return response()->json([
                'message' => 'User found successfully.',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }
    }
}
