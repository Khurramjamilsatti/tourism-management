<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['name', 'code'];

    public function tourismData()
    {
        return $this->hasMany(TourismData::class);
    }
}
