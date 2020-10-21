<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gym extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'neighborhood', 'street', 'number', 'complement', 'zipcode', 'city', 'phone', 'email', 'password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
}
