<?php

namespace App\Http\Controllers;

use App\Models\AgeGroup;
use Illuminate\Http\Request;

class AgeGroupController extends Controller
{
    public function index()
    {
        $ageGroups = AgeGroup::withCount('tourismData')->orderBy('name')->paginate(15);
        return view('age-groups.index', compact('ageGroups'));
    }

    public function create()
    {
        return view('age-groups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:age_groups,name',
        ]);

        AgeGroup::create($validated);

        return redirect()->route('age-groups.index')
            ->with('success', 'Age group added successfully.');
    }

    public function edit(AgeGroup $ageGroup)
    {
        return view('age-groups.edit', compact('ageGroup'));
    }

    public function update(Request $request, AgeGroup $ageGroup)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:age_groups,name,' . $ageGroup->id,
        ]);

        $ageGroup->update($validated);

        return redirect()->route('age-groups.index')
            ->with('success', 'Age group updated successfully.');
    }

    public function destroy(AgeGroup $ageGroup)
    {
        if ($ageGroup->tourismData()->exists()) {
            return redirect()->route('age-groups.index')
                ->with('error', 'Cannot delete age group with existing tourism records.');
        }

        $ageGroup->delete();

        return redirect()->route('age-groups.index')
            ->with('success', 'Age group deleted successfully.');
    }
}
