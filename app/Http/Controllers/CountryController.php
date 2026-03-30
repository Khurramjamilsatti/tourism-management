<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::withCount('tourismData')->orderBy('name')->paginate(15);
        return view('countries.index', compact('countries'));
    }

    public function create()
    {
        return view('countries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:countries,name',
            'code' => 'nullable|string|max:10',
        ]);

        Country::create($validated);

        return redirect()->route('countries.index')
            ->with('success', 'Country added successfully.');
    }

    public function edit(Country $country)
    {
        return view('countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:countries,name,' . $country->id,
            'code' => 'nullable|string|max:10',
        ]);

        $country->update($validated);

        return redirect()->route('countries.index')
            ->with('success', 'Country updated successfully.');
    }

    public function destroy(Country $country)
    {
        if ($country->tourismData()->exists()) {
            return redirect()->route('countries.index')
                ->with('error', 'Cannot delete country with existing tourism records.');
        }

        $country->delete();

        return redirect()->route('countries.index')
            ->with('success', 'Country deleted successfully.');
    }
}
