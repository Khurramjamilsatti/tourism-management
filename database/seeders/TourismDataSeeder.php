<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\TourismData;
use App\Models\User;
use App\Models\VisitPurpose;
use App\Models\AgeGroup;
use App\Models\TravelType;
use App\Models\BudgetCategory;
use Illuminate\Database\Seeder;

class TourismDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing tourism data
        TourismData::truncate();

        $cities = [
            'London', 'Paris', 'New York', 'Tokyo', 'Sydney', 'Berlin', 'Rome',
            'Barcelona', 'Toronto', 'Mumbai', 'Seoul', 'Amsterdam', 'Dubai',
            'Singapore', 'Bangkok', 'Istanbul', 'Prague', 'Vienna', 'Lisbon',
            'Athens', 'Cairo', 'Cape Town', 'Rio de Janeiro', 'Buenos Aires',
            'Mexico City', 'Los Angeles', 'San Francisco', 'Chicago', 'Miami',
            'Vancouver', 'Edinburgh', 'Dublin', 'Zurich', 'Copenhagen',
            'Stockholm', 'Helsinki', 'Oslo', 'Reykjavik', 'Marrakech', 'Bali',
        ];

        $feedbacks = [
            'Wonderful experience! Great hospitality.',
            'Beautiful city with amazing architecture.',
            'Had a great time exploring local culture.',
            'Business meetings went very well.',
            'The food was incredible!',
            'Will definitely visit again.',
            'Great conference venue and networking.',
            null,
            'Loved the historical sites.',
            'Very friendly locals.',
            'Excellent transport system.',
            'The nightlife was fantastic.',
            'Perfect for a family vacation.',
            'Great shopping opportunities.',
            'The museums were world-class.',
            'Would recommend to everyone.',
            'A bit expensive but worth it.',
            'The weather was perfect.',
            'Amazing natural scenery.',
            'Rich cultural heritage.',
        ];

        $countryIds = Country::pluck('id')->toArray();
        $purposeIds = VisitPurpose::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();
        $ageGroups = AgeGroup::pluck('name')->toArray();
        $travelTypes = TravelType::pluck('name')->toArray();
        $budgets = BudgetCategory::pluck('name')->toArray();

        // Fallbacks in case lookup tables are empty
        if (empty($ageGroups)) {
            $ageGroups = ['18-25', '26-35', '36-45', '46-55', '56-65', '65+'];
        }
        if (empty($travelTypes)) {
            $travelTypes = ['Solo', 'Couple', 'Family', 'Group', 'Business'];
        }
        if (empty($budgets)) {
            $budgets = ['Low', 'Medium', 'High', 'Luxury'];
        }

        $batchSize = 100;
        $total = 10000;
        $records = [];

        for ($i = 0; $i < $total; $i++) {
            $visitDate = fake()->dateTimeBetween('-24 months', 'now');
            $records[] = [
                'visitor_name' => fake()->optional(0.8)->name(),
                'country_id' => $countryIds[array_rand($countryIds)],
                'city_visited' => $cities[array_rand($cities)],
                'purpose_id' => $purposeIds[array_rand($purposeIds)],
                'visit_date' => $visitDate->format('Y-m-d'),
                'month' => (int) $visitDate->format('m'),
                'age_group' => $ageGroups[array_rand($ageGroups)],
                'travel_type' => $travelTypes[array_rand($travelTypes)],
                'budget' => $budgets[array_rand($budgets)],
                'duration' => fake()->numberBetween(1, 30),
                'satisfaction' => fake()->numberBetween(1, 5),
                'previous_visits' => fake()->numberBetween(0, 10),
                'spending' => fake()->randomFloat(2, 50, 10000),
                'will_return' => fake()->boolean(70),
                'feedback' => $feedbacks[array_rand($feedbacks)],
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($records) >= $batchSize) {
                TourismData::insert($records);
                $records = [];
            }
        }

        if (!empty($records)) {
            TourismData::insert($records);
        }
    }
}
