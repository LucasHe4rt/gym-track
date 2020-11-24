<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'neighborhood', 'street', 'number', 'complement', 'zipcode', 'city', 'phone', 'email', 'password',
        'birthday', 'sex', 'height', 'blood', 'weight', 'gym_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];



    public function gym(){
        return $this->belongsTo(Gym::class);
    }

    public function emergencyContacts(){
        return $this->hasMany(EmergencyContacts::class);
    }

    public function medicalConditions(){
        return $this->hasMany(MedicalConditions::class);
    }




}
