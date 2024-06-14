# CRUD Package for Laravel 10+

## Project Overview

This project involves the creation of a Laravel package that generates complete CRUD operations for an API. The package automates the generation of controllers, models, validation files, migration files, and API routes. When integrated into a Laravel project, it updates the `routes/api.php` file with the required API routes and creates all necessary files for a fully functional CRUD operation. The key requirements for this project include:

- Compatibility with Laravel 10+
- Adherence to the MVC code pattern
- Clean, maintainable code

## Project Installation

To install and use this package in a Laravel 10 project, follow these steps:

### Install dependencies:
```
composer install
```
### Set up your environment variables:
Configure your .env file with the following database information:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crud_pro
DB_USERNAME=root
DB_PASSWORD=
```
### Run the migrations to create the required database table:
```php artisan make:migration create_user_crud_table
php artisan migrate
```
## Project Schema:

Here, the migration file is displayed, which creates the user_crud table in the database. It defines the structure of the table with fields such as name, email, address, gender, phone, and timestamps.


The migration file creates a table named user_crud with the following structure:
```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_crud', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email'); 
            $table->string('address');
            $table->string('gender');
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_crud');
    }
};
```

## Controllers
### UserCrudController
This controller handles listing all users.

#### Each controller handles specific CRUD operations:

- UserCrudController : Lists all users (index method).

- CreateUserController: Handles user creation (store method) with validation and error handling.
- UpdateUserController: Manages user updates (update method) with validation and error handling, ensuring uniqueness of email and validating other fields.
- FindUserController: Retrieves details of a specific user (show method).
- DeleteUserController: Deletes a user (destroy method).


```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_crud;

class UserCrudController extends Controller
{
    protected $users;

    public function __construct(){
        $this->users = new User_crud();
    }

    public function index()
    {
        return $this->users->all();
    }
}
```
Each controller is constructed to interact with the user_crud model and perform its respective CRUD operation via API endpoints.

 
## CreateUserController
### This controller handles creating new users.
```
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

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'phone' => 'required|string|regex:/^\d+$/|max:15'
        ];

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

        try {
            $validatedData = $request->validate($rules, $messages);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $user = $this->users->create($validatedData);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }
}
```

## UpdateUserController
### This controller handles updating existing users.

```
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
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'address' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'phone' => 'required|string|regex:/^\d+$/|max:15'
        ];

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

        $update_user = $this->users->find($id);

        if (!$update_user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        try {
            $validatedData = $request->validate($rules, $messages);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $update_user->update($validatedData);
        return response()->json(['message' => 'User updated successfully', 'update_user' => $update_user], 200);
    }
}
```

## FindUserController
## This controller handles fetching details of a specific user.

```
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

    public function show(Request $request, string $id)
    {
        $user = $this->users->find($id);
        
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

```

## DeleteUserController
### This controller handles deleting a user.

```
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
        $delete_user = $this->users->find($id);
        
        if (!$delete_user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $delete_user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }
}
```
## Project API Routes
### Below are the API routes for the CRUD operations:
This section lists the API routes created for the project, specifying HTTP methods and controller methods associated with each route:

- /users: GET request to list all users.
- /user-find/{id}: GET request to fetch details of a specific user by ID.
- /user-edit/{id}: PUT request to update a user by ID.
- /user-create: POST request to create a new user.
- /user-delete/{id}: DELETE request to delete a user by ID.

```
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserCrudController;
use App\Http\Controllers\UpdateUserController;
use App\Http\Controllers\CreateUserController;
use App\Http\Controllers\DeleteUserController;
use App\Http\Controllers\FindUserController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::GET('/users', [UserCrudController::class, 'index']);
Route::GET('/user-find/{id}', [FindUserController::class, 'show']);
Route::PUT('/user-edit/{id}', [UpdateUserController::class, 'update']);
Route::POST('/user-create', [CreateUserController::class, 'store']);
Route::DELETE('/user-delete/{id}', [DeleteUserController::class, 'destroy']);
```
These routes collectively enable the full CRUD functionality for managing users in the Laravel application.


## API ScreenShort
### User is Craeted

<code><img alt="certified scrum master" src="https://github.com/coderskamrul/laravel-crud/blob/main/crud-project/img/User%20created.jpg"></code>

### Show All User
<code><img alt="certified scrum master" src="https://github.com/coderskamrul/laravel-crud/blob/main/crud-project/img/get%20all%20user%20api.jpg"></code>

### Show Specific User Base on ID
<code><img alt="certified scrum master" src="https://github.com/coderskamrul/laravel-crud/blob/main/crud-project/img/sepecific%20user%20found.jpg"></code>

### User Updated Base on ID
<code><img alt="certified scrum master" src="https://github.com/coderskamrul/laravel-crud/blob/main/crud-project/img/user%20updated.jpg"></code>

### User Deleted Base on ID
<code><img alt="certified scrum master" src="https://github.com/coderskamrul/laravel-crud/blob/main/crud-project/img/user%20deleted.jpg"></code>

### User Validation when User Create and Updated
<code><img alt="certified scrum master" src="https://github.com/coderskamrul/laravel-crud/blob/main/crud-project/img/validation%20user%20data.jpg"></code>

