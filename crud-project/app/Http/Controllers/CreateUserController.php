<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_crud;
use Illuminate\Validation\ValidationException;

class CreateUserController extends Controller
{
    protected $users;

    public function __construct()
    {
        $this->users = new User_crud();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Define validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
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

        // Validate the request
        try {
            $validatedData = $request->validate($rules, $messages);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        // Create User
        $user = $this->users->create($validatedData);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }
}
