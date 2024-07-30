<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{

    public function client()
    {
        // Explicitly define the foreign key if it does not follow Laravel's convention
        return $this->belongsTo(Client::class, 'client');
    }
    protected $fillable = [
        'client',
        'serial_no',
        'amount',
        'status',
        'title',
        'size',
        'lat_lng',
        'type',
        'land_type',
        'no_bedrooms',
        'no_toilets',
        'no_majlis',
        'no_floors',
        'no_kitchens',
        'media',  // Make sure to include all fields used in mass assignments
        'created_by',
        'updated_by'
    ];

    use HasFactory;
}
