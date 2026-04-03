<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\User;
use App\Models\VisitPurpose;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@tourism.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create Staff user
        $staff = User::firstOrCreate(
            ['email' => 'staff@tourism.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );

        // Create Countries (~100)
        $this->call(CountrySeeder::class);

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
            VisitPurpose::firstOrCreate(['name' => $purpose]);
        }

        // Seed lookup tables (Age Groups, Travel Types, Budget Categories)
        $this->call(LookupSeeder::class);

        // Seed 1000 tourism data records
        $this->call(TourismDataSeeder::class);
    }
}
