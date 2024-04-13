<?php

// The MODEL is a representation of the table
// in the database (it serve as the connection to the databse).
// Through the model, we can execute queries to the table
// -- we insert data, create data, read data, update data and delete, etc.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
}


