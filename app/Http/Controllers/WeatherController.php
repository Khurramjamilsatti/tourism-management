<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function getWeather(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:255',
        ]);

        $city = $request->city;

        try {
            // Using Open-Meteo free API (no API key required)
            // First geocode the city
            $geoResponse = Http::get('https://geocoding-api.open-meteo.com/v1/search', [
                'name' => $city,
                'count' => 1,
            ]);

            if (!$geoResponse->successful() || empty($geoResponse->json('results'))) {
                return response()->json(['error' => 'City not found'], 404);
            }

            $location = $geoResponse->json('results.0');
            $lat = $location['latitude'];
            $lon = $location['longitude'];

            // Get weather data
            $weatherResponse = Http::get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => $lat,
                'longitude' => $lon,
                'current' => 'temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m',
                'timezone' => 'auto',
            ]);

            if (!$weatherResponse->successful()) {
                return response()->json(['error' => 'Weather data unavailable'], 500);
            }

            $current = $weatherResponse->json('current');

            return response()->json([
                'city' => $location['name'],
                'country' => $location['country'] ?? '',
                'temperature' => $current['temperature_2m'],
                'humidity' => $current['relative_humidity_2m'],
                'wind_speed' => $current['wind_speed_10m'],
                'weather_code' => $current['weather_code'],
                'weather_description' => $this->getWeatherDescription($current['weather_code']),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch weather data'], 500);
        }
    }

    private function getWeatherDescription(int $code): string
    {
        $descriptions = [
            0 => 'Clear sky',
            1 => 'Mainly clear',
            2 => 'Partly cloudy',
            3 => 'Overcast',
            45 => 'Foggy',
            48 => 'Depositing rime fog',
            51 => 'Light drizzle',
            53 => 'Moderate drizzle',
            55 => 'Dense drizzle',
            61 => 'Slight rain',
            63 => 'Moderate rain',
            65 => 'Heavy rain',
            71 => 'Slight snow',
            73 => 'Moderate snow',
            75 => 'Heavy snow',
            80 => 'Slight rain showers',
            81 => 'Moderate rain showers',
            82 => 'Violent rain showers',
            95 => 'Thunderstorm',
            96 => 'Thunderstorm with slight hail',
            99 => 'Thunderstorm with heavy hail',
        ];

        return $descriptions[$code] ?? 'Unknown';
    }
}
