<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelType extends Model
{
    protected $fillable = ['name'];

    public function tourismData()
    {
        return $this->hasMany(TourismData::class, 'travel_type', 'name');
    }
}
