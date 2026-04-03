<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\TourismData;
use App\Models\VisitPurpose;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalVisitors = TourismData::count();
        $totalCountries = Country::has('tourismData')->count();
        $totalPurposes = VisitPurpose::has('tourismData')->count();
        $recentEntries = TourismData::with(['country', 'purpose'])->latest()->take(5)->get();

        // Visitors per country
        $visitorsByCountry = TourismData::select('country_id', DB::raw('count(*) as total'))
            ->groupBy('country_id')
            ->with('country')
            ->get()
            ->map(fn($item) => [
                'country' => $item->country->name,
                'total' => $item->total,
            ]);

        // Monthly trends (last 12 months)
        $monthlyTrends = TourismData::select(
                DB::raw("DATE_FORMAT(visit_date, '%Y-%m') as month"),
                DB::raw('count(*) as total')
            )
            ->where('visit_date', '>=', now()->subMonths(12))
            ->groupBy(DB::raw("DATE_FORMAT(visit_date, '%Y-%m')"))
            ->orderBy('month')
            ->get();

        // Purpose distribution
        $purposeDistribution = TourismData::select('purpose_id', DB::raw('count(*) as total'))
            ->groupBy('purpose_id')
            ->with('purpose')
            ->get()
            ->map(fn($item) => [
                'purpose' => $item->purpose->name,
                'total' => $item->total,
            ]);

        return view('dashboard.index', compact(
            'totalVisitors',
            'totalCountries',
            'totalPurposes',
            'recentEntries',
            'visitorsByCountry',
            'monthlyTrends',
            'purposeDistribution'
        ));
    }
}
