<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_crud;
use Illuminate\Validation\ValidationException;

class UpdateUserController extends Controller
{
    protected $users;

    public function __construct(){
        $this->users = new User_crud();
    }

    public function update(Request $request, string $id)
    {
        // Define validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'address' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'phone' => 'required|string|regex:/^\d+$/|max:15'
        ];

        // Define custom error messages (optional)
        $messages = [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'Email already exists',
            'address.required' => 'Address is required',
            'gender.required' => 'Gender is required',
            'gender.in' => 'Gender must be one of the following: Male, Female, Other',
            'phone.required' => 'Phone is required',
            'phone.regex' => 'Phone number must contain only digits',
            'phone.max' => 'Phone number must not exceed 15 characters'
        ];

        // Find the user by ID
        $update_user = $this->users->find($id);

        if (!$update_user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validate the request
        try {
            $validatedData = $request->validate($rules, $messages);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        // Update the user with the request data
        $update_user->update($validatedData);
        return response()->json(['message' => 'User updated successfully', 'update_user' => $update_user], 200);
    }
}
