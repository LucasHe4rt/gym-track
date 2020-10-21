<?php


namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $table = 'instructors';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'arrive',
        'leave',
        'gym_id'
    ];

    protected $hidden = [
        'password'
    ];
}
