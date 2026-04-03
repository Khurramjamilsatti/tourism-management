<?php

namespace App\Http\Controllers;

use App\Models\BudgetCategory;
use Illuminate\Http\Request;

class BudgetCategoryController extends Controller
{
    public function index()
    {
        $budgetCategories = BudgetCategory::withCount('tourismData')->orderBy('name')->paginate(15);
        return view('budget-categories.index', compact('budgetCategories'));
    }

    public function create()
    {
        return view('budget-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:budget_categories,name',
        ]);

        BudgetCategory::create($validated);

        return redirect()->route('budget-categories.index')
            ->with('success', 'Budget category added successfully.');
    }

    public function edit(BudgetCategory $budgetCategory)
    {
        return view('budget-categories.edit', compact('budgetCategory'));
    }

    public function update(Request $request, BudgetCategory $budgetCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:budget_categories,name,' . $budgetCategory->id,
        ]);

        $budgetCategory->update($validated);

        return redirect()->route('budget-categories.index')
            ->with('success', 'Budget category updated successfully.');
    }

    public function destroy(BudgetCategory $budgetCategory)
    {
        if ($budgetCategory->tourismData()->exists()) {
            return redirect()->route('budget-categories.index')
                ->with('error', 'Cannot delete budget category with existing tourism records.');
        }

        $budgetCategory->delete();

        return redirect()->route('budget-categories.index')
            ->with('success', 'Budget category deleted successfully.');
    }
}
