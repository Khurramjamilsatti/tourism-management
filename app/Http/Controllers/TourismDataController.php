<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\TourismData;
use App\Models\VisitPurpose;
use Illuminate\Http\Request;

class TourismDataController extends Controller
{
    public function index(Request $request)
    {
        $query = TourismData::with(['country', 'purpose', 'creator']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('visitor_name', 'like', "%{$search}%")
                  ->orWhere('city_visited', 'like', "%{$search}%");
            });
        }

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('purpose_id')) {
            $query->where('purpose_id', $request->purpose_id);
        }

        if ($request->filled('date_from')) {
            $query->where('visit_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('visit_date', '<=', $request->date_to);
        }

        $records = $query->latest()->paginate(15)->withQueryString();
        $countries = Country::orderBy('name')->get();
        $purposes = VisitPurpose::orderBy('name')->get();

        return view('tourism-data.index', compact('records', 'countries', 'purposes'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();
        $purposes = VisitPurpose::orderBy('name')->get();
        return view('tourism-data.create', compact('countries', 'purposes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'visitor_name' => 'nullable|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'city_visited' => 'required|string|max:255',
            'purpose_id' => 'required|exists:visit_purposes,id',
            'visit_date' => 'required|date',
            'feedback' => 'nullable|string|max:2000',
        ]);

        $validated['created_by'] = auth()->id();

        TourismData::create($validated);

        return redirect()->route('tourism-data.index')
            ->with('success', 'Tourism data record created successfully.');
    }

    public function edit(TourismData $tourismData)
    {
        $countries = Country::orderBy('name')->get();
        $purposes = VisitPurpose::orderBy('name')->get();
        return view('tourism-data.edit', compact('tourismData', 'countries', 'purposes'));
    }

    public function update(Request $request, TourismData $tourismData)
    {
        $validated = $request->validate([
            'visitor_name' => 'nullable|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'city_visited' => 'required|string|max:255',
            'purpose_id' => 'required|exists:visit_purposes,id',
            'visit_date' => 'required|date',
            'feedback' => 'nullable|string|max:2000',
        ]);

        $tourismData->update($validated);

        return redirect()->route('tourism-data.index')
            ->with('success', 'Tourism data record updated successfully.');
    }

    public function destroy(TourismData $tourismData)
    {
        $tourismData->delete();

        return redirect()->route('tourism-data.index')
            ->with('success', 'Tourism data record deleted successfully.');
    }
}
