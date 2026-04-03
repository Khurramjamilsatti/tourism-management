<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\TourismData;
use App\Models\VisitPurpose;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('name')->get();
        $purposes = VisitPurpose::orderBy('name')->get();
        return view('reports.index', compact('countries', 'purposes'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'country_id' => 'nullable|exists:countries,id',
            'purpose_id' => 'nullable|exists:visit_purposes,id',
        ]);

        $query = TourismData::with(['country', 'purpose', 'creator']);

        if ($request->filled('date_from')) {
            $query->where('visit_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('visit_date', '<=', $request->date_to);
        }
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }
        if ($request->filled('purpose_id')) {
            $query->where('purpose_id', $request->purpose_id);
        }

        $records = $query->orderBy('visit_date', 'desc')->paginate(100)->withQueryString();

        // Summary stats
        $summary = [
            'total_records' => $records->total(),
            'countries' => $records->pluck('country.name')->unique()->count(),
            'cities' => $records->pluck('city_visited')->unique()->count(),
            'date_range' => $request->date_from . ' to ' . $request->date_to,
        ];

        $countries = Country::orderBy('name')->get();
        $purposes = VisitPurpose::orderBy('name')->get();

        return view('reports.index', compact('records', 'summary', 'countries', 'purposes'));
    }

    public function exportCsv(Request $request)
    {
        $query = TourismData::with(['country', 'purpose', 'creator']);

        if ($request->filled('date_from')) {
            $query->where('visit_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('visit_date', '<=', $request->date_to);
        }
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }
        if ($request->filled('purpose_id')) {
            $query->where('purpose_id', $request->purpose_id);
        }

        $records = $query->orderBy('visit_date', 'desc')->get();

        $filename = 'tourism_report_' . now()->format('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($records) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['#', 'Visitor Name', 'Country', 'City Visited', 'Purpose', 'Visit Date', 'Feedback', 'Recorded By']);

            foreach ($records as $index => $record) {
                fputcsv($file, [
                    $index + 1,
                    $record->visitor_name ?? 'N/A',
                    $record->country->name,
                    $record->city_visited,
                    $record->purpose->name,
                    $record->visit_date->format('Y-m-d'),
                    $record->feedback ?? '',
                    $record->creator->name,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
