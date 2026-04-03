<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetCategory extends Model
{
    protected $fillable = ['name'];

    public function tourismData()
    {
        return $this->hasMany(TourismData::class, 'budget', 'name');
    }
}
