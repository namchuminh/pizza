<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'position',
        'date_of_birth',
        'id_card_number',
        'date_of_issue',
        'place_of_issue',
        'start_date',
        'salary'
    ];
}
