<?php

namespace App\Http\Controllers;

use App\Models\TourismData;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Visitors by country (bar chart)
        $visitorsByCountry = TourismData::select('country_id', DB::raw('count(*) as total'))
            ->groupBy('country_id')
            ->with('country')
            ->get()
            ->map(fn($item) => [
                'label' => $item->country->name,
                'value' => $item->total,
            ]);

        // Monthly trends (line chart - last 12 months)
        $monthlyTrends = TourismData::select(
                DB::raw("DATE_FORMAT(visit_date, '%Y-%m') as month"),
                DB::raw('count(*) as total')
            )
            ->where('visit_date', '>=', now()->subMonths(12))
            ->groupBy(DB::raw("DATE_FORMAT(visit_date, '%Y-%m')"))
            ->orderBy('month')
            ->get();

        // Purpose of visit (pie chart)
        $purposeDistribution = TourismData::select('purpose_id', DB::raw('count(*) as total'))
            ->groupBy('purpose_id')
            ->with('purpose')
            ->get()
            ->map(fn($item) => [
                'label' => $item->purpose->name,
                'value' => $item->total,
            ]);

        // Top cities
        $topCities = TourismData::select('city_visited', DB::raw('count(*) as total'))
            ->groupBy('city_visited')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        return view('analytics.index', compact(
            'visitorsByCountry',
            'monthlyTrends',
            'purposeDistribution',
            'topCities'
        ));
    }
}
