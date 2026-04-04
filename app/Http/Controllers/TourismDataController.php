<?php

namespace App\Http\Controllers;

use App\Models\AgeGroup;
use App\Models\BudgetCategory;
use App\Models\Country;
use App\Models\TourismData;
use App\Models\TravelType;
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

        if ($request->filled('age_group')) {
            // URL decodes '+' as space, so restore it (e.g. "65+" in URL becomes "65 ")
            $ageGroup = str_replace(' ', '+', $request->age_group);
            $query->where('age_group', $ageGroup);
        }

        if ($request->filled('travel_type')) {
            $query->where('travel_type', $request->travel_type);
        }

        if ($request->filled('budget')) {
            $query->where('budget', $request->budget);
        }

        $records = $query->latest()->paginate(100)->withQueryString();
        $countries = Country::orderBy('name')->get();
        $purposes = VisitPurpose::orderBy('name')->get();
        $ageGroups = AgeGroup::orderBy('name')->get();
        $travelTypes = TravelType::orderBy('name')->get();
        $budgetCategories = BudgetCategory::orderBy('name')->get();

        return view('tourism-data.index', compact('records', 'countries', 'purposes', 'ageGroups', 'travelTypes', 'budgetCategories'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();
        $purposes = VisitPurpose::orderBy('name')->get();
        $ageGroups = AgeGroup::orderBy('name')->get();
        $travelTypes = TravelType::orderBy('name')->get();
        $budgetCategories = BudgetCategory::orderBy('name')->get();
        return view('tourism-data.create', compact('countries', 'purposes', 'ageGroups', 'travelTypes', 'budgetCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'visitor_name' => 'nullable|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'city_visited' => 'required|string|max:255',
            'purpose_id' => 'required|exists:visit_purposes,id',
            'visit_date' => 'required|date',
            'month' => 'nullable|integer|min:1|max:12',
            'age_group' => 'nullable|string|max:50',
            'travel_type' => 'nullable|string|max:50',
            'budget' => 'nullable|string|max:50',
            'duration' => 'nullable|integer|min:1',
            'satisfaction' => 'nullable|integer|min:1|max:5',
            'previous_visits' => 'nullable|integer|min:0',
            'spending' => 'nullable|numeric|min:0',
            'will_return' => 'nullable|boolean',
            'feedback' => 'nullable|string|max:2000',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['will_return'] = $request->has('will_return') ? 1 : 0;

        TourismData::create($validated);

        return redirect()->route('tourism-data.index')
            ->with('success', 'Tourism data record created successfully.');
    }

    public function edit(TourismData $tourismData)
    {
        $countries = Country::orderBy('name')->get();
        $purposes = VisitPurpose::orderBy('name')->get();
        $ageGroups = AgeGroup::orderBy('name')->get();
        $travelTypes = TravelType::orderBy('name')->get();
        $budgetCategories = BudgetCategory::orderBy('name')->get();
        return view('tourism-data.edit', compact('tourismData', 'countries', 'purposes', 'ageGroups', 'travelTypes', 'budgetCategories'));
    }

    public function update(Request $request, TourismData $tourismData)
    {
        $validated = $request->validate([
            'visitor_name' => 'nullable|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'city_visited' => 'required|string|max:255',
            'purpose_id' => 'required|exists:visit_purposes,id',
            'visit_date' => 'required|date',
            'month' => 'nullable|integer|min:1|max:12',
            'age_group' => 'nullable|string|max:50',
            'travel_type' => 'nullable|string|max:50',
            'budget' => 'nullable|string|max:50',
            'duration' => 'nullable|integer|min:1',
            'satisfaction' => 'nullable|integer|min:1|max:5',
            'previous_visits' => 'nullable|integer|min:0',
            'spending' => 'nullable|numeric|min:0',
            'will_return' => 'nullable|boolean',
            'feedback' => 'nullable|string|max:2000',
        ]);

        $validated['will_return'] = $request->has('will_return') ? 1 : 0;

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
