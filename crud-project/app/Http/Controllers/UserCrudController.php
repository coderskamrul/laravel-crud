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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Show all users from the database table
        return $this->users->all();
    }

}
