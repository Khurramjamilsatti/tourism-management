<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourismData extends Model
{
    protected $table = 'tourism_data';

    protected $fillable = [
        'visitor_name',
        'country_id',
        'city_visited',
        'purpose_id',
        'visit_date',
        'feedback',
        'created_by',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function purpose()
    {
        return $this->belongsTo(VisitPurpose::class, 'purpose_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
