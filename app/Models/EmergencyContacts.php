<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyContacts extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'neighborhood', 'street', 'number', 'complement', 'zipcode', 'city', 'phone', 'client_id'
    ];

    public function client(){
        return $this->belongsTo(Client::class);
    }




}
