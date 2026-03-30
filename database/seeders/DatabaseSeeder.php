<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\TourismData;
use App\Models\User;
use App\Models\VisitPurpose;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@tourism.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Staff user
        $staff = User::create([
            'name' => 'Staff User',
            'email' => 'staff@tourism.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // Create Countries
        $countries = [
            ['name' => 'United States', 'code' => 'US'],
            ['name' => 'United Kingdom', 'code' => 'UK'],
            ['name' => 'Germany', 'code' => 'DE'],
            ['name' => 'France', 'code' => 'FR'],
            ['name' => 'Japan', 'code' => 'JP'],
            ['name' => 'Australia', 'code' => 'AU'],
            ['name' => 'Canada', 'code' => 'CA'],
            ['name' => 'Brazil', 'code' => 'BR'],
            ['name' => 'India', 'code' => 'IN'],
            ['name' => 'China', 'code' => 'CN'],
            ['name' => 'Italy', 'code' => 'IT'],
            ['name' => 'Spain', 'code' => 'ES'],
            ['name' => 'Mexico', 'code' => 'MX'],
            ['name' => 'South Korea', 'code' => 'KR'],
            ['name' => 'Netherlands', 'code' => 'NL'],
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }

        // Create Visit Purposes
        $purposes = [
            'Tourism',
            'Business',
            'Education',
            'Medical',
            'Conference',
            'Family Visit',
            'Adventure',
            'Cultural Exchange',
        ];

        foreach ($purposes as $purpose) {
            VisitPurpose::create(['name' => $purpose]);
        }

        // Create sample tourism data
        $cities = ['London', 'Paris', 'New York', 'Tokyo', 'Sydney', 'Berlin', 'Rome', 'Barcelona', 'Toronto', 'Mumbai', 'Seoul', 'Amsterdam'];
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
        ];

        $countryIds = Country::pluck('id')->toArray();
        $purposeIds = VisitPurpose::pluck('id')->toArray();

        for ($i = 0; $i < 50; $i++) {
            TourismData::create([
                'visitor_name' => fake()->optional(0.7)->name(),
                'country_id' => $countryIds[array_rand($countryIds)],
                'city_visited' => $cities[array_rand($cities)],
                'purpose_id' => $purposeIds[array_rand($purposeIds)],
                'visit_date' => fake()->dateTimeBetween('-12 months', 'now')->format('Y-m-d'),
                'feedback' => $feedbacks[array_rand($feedbacks)],
                'created_by' => [$admin->id, $staff->id][array_rand([$admin->id, $staff->id])],
            ]);
        }
    }
}
