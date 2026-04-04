<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TourismData extends Model
{
    protected $table = 'tourism_data';

    protected $fillable = [
        'visitor_name',
        'country_id',
        'city_visited',
        'purpose_id',
        'visit_date',
        'month',
        'age_group',
        'travel_type',
        'budget',
        'duration',
        'satisfaction',
        'previous_visits',
        'spending',
        'will_return',
        'feedback',
        'created_by',
    ];

    protected $casts = [
        'month' => 'integer',
        'duration' => 'integer',
        'satisfaction' => 'integer',
        'previous_visits' => 'integer',
        'spending' => 'decimal:2',
        'will_return' => 'boolean',
    ];

    protected function visitDate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (empty($value) || $value === '0000-00-00') {
                    return null;
                }
                try {
                    return Carbon::parse($value);
                } catch (\Throwable $e) {
                    return null;
                }
            },
        );
    }

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
