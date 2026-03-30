<?php

namespace App\Http\Controllers;

use App\Models\VisitPurpose;
use Illuminate\Http\Request;

class VisitPurposeController extends Controller
{
    public function index()
    {
        $purposes = VisitPurpose::withCount('tourismData')->orderBy('name')->paginate(15);
        return view('visit-purposes.index', compact('purposes'));
    }

    public function create()
    {
        return view('visit-purposes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:visit_purposes,name',
        ]);

        VisitPurpose::create($validated);

        return redirect()->route('visit-purposes.index')
            ->with('success', 'Visit purpose added successfully.');
    }

    public function edit(VisitPurpose $visitPurpose)
    {
        return view('visit-purposes.edit', compact('visitPurpose'));
    }

    public function update(Request $request, VisitPurpose $visitPurpose)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:visit_purposes,name,' . $visitPurpose->id,
        ]);

        $visitPurpose->update($validated);

        return redirect()->route('visit-purposes.index')
            ->with('success', 'Visit purpose updated successfully.');
    }

    public function destroy(VisitPurpose $visitPurpose)
    {
        if ($visitPurpose->tourismData()->exists()) {
            return redirect()->route('visit-purposes.index')
                ->with('error', 'Cannot delete purpose with existing tourism records.');
        }

        $visitPurpose->delete();

        return redirect()->route('visit-purposes.index')
            ->with('success', 'Visit purpose deleted successfully.');
    }
}
