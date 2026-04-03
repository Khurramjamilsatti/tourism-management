<?php

namespace App\Http\Controllers;

use App\Models\TravelType;
use Illuminate\Http\Request;

class TravelTypeController extends Controller
{
    public function index()
    {
        $travelTypes = TravelType::withCount('tourismData')->orderBy('name')->paginate(15);
        return view('travel-types.index', compact('travelTypes'));
    }

    public function create()
    {
        return view('travel-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:travel_types,name',
        ]);

        TravelType::create($validated);

        return redirect()->route('travel-types.index')
            ->with('success', 'Travel type added successfully.');
    }

    public function edit(TravelType $travelType)
    {
        return view('travel-types.edit', compact('travelType'));
    }

    public function update(Request $request, TravelType $travelType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:travel_types,name,' . $travelType->id,
        ]);

        $travelType->update($validated);

        return redirect()->route('travel-types.index')
            ->with('success', 'Travel type updated successfully.');
    }

    public function destroy(TravelType $travelType)
    {
        if ($travelType->tourismData()->exists()) {
            return redirect()->route('travel-types.index')
                ->with('error', 'Cannot delete travel type with existing tourism records.');
        }

        $travelType->delete();

        return redirect()->route('travel-types.index')
            ->with('success', 'Travel type deleted successfully.');
    }
}
