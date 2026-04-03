<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgeGroup extends Model
{
    protected $fillable = ['name'];

    public function tourismData()
    {
        return $this->hasMany(TourismData::class, 'age_group', 'name');
    }
}
