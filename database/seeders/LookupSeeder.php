<?php

namespace Database\Seeders;

use App\Models\AgeGroup;
use App\Models\BudgetCategory;
use App\Models\TravelType;
use Illuminate\Database\Seeder;

class LookupSeeder extends Seeder
{
    public function run(): void
    {
        // Age Groups
        $ageGroups = ['18-25', '26-35', '36-45', '46-55', '56-65', '65+'];
        foreach ($ageGroups as $name) {
            AgeGroup::firstOrCreate(['name' => $name]);
        }

        // Travel Types
        $travelTypes = ['Solo', 'Couple', 'Family', 'Group', 'Business'];
        foreach ($travelTypes as $name) {
            TravelType::firstOrCreate(['name' => $name]);
        }

        // Budget Categories
        $budgetCategories = ['Low', 'Medium', 'High', 'Luxury'];
        foreach ($budgetCategories as $name) {
            BudgetCategory::firstOrCreate(['name' => $name]);
        }
    }
}
