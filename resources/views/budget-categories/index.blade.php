@extends('layouts.app')

@section('title', 'Budget Categories')
@section('page-title', 'Budget Categories Management')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0">All Budget Categories</h6>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('budget-categories.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Add Budget Category
        </a>
        @endif
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Records</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($budgetCategories as $budget)
                <tr>
                    <td>{{ $loop->iteration + ($budgetCategories->currentPage() - 1) * $budgetCategories->perPage() }}</td>
                    <td>{{ $budget->name }}</td>
                    <td><span class="badge bg-primary">{{ $budget->tourism_data_count }}</span></td>
                    <td>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('budget-categories.edit', $budget) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('budget-categories.destroy', $budget) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this budget category?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">No budget categories added yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($budgetCategories->hasPages())
    <div class="card-footer bg-white">{{ $budgetCategories->links() }}</div>
    @endif
</div>
@endsection
