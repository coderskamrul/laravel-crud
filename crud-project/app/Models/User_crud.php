<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_crud extends Model
{
    protected $table = 'user_crud';
    protected $primaryKey = 'id';
     protected $fillable = [
          'name',
          'email',
          'address',
          'gender',
          'phone',
      ];

    use HasFactory;
}
