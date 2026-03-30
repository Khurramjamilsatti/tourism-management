<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitPurpose extends Model
{
    protected $fillable = ['name'];

    public function tourismData()
    {
        return $this->hasMany(TourismData::class, 'purpose_id');
    }
}
