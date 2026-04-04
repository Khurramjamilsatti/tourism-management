@extends('layouts.app')

@section('title', 'Age Groups')
@section('page-title', 'Age Groups Management')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0">All Age Groups</h6>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('age-groups.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Add Age Group
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
                @forelse($ageGroups as $ageGroup)
                <tr>
                    <td>{{ $loop->iteration + ($ageGroups->currentPage() - 1) * $ageGroups->perPage() }}</td>
                    <td>{{ $ageGroup->name }}</td>
                    <td><a href="{{ URL::to('tourism-data?age_group=' . $ageGroup->name) }}" class="badge rounded-pill bg-primary text-decoration-none">{{ $ageGroup->tourism_data_count }}</a></td>
                    <td>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('age-groups.edit', $ageGroup) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('age-groups.destroy', $ageGroup) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this age group?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">No age groups added yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($ageGroups->hasPages())
    <div class="card-footer bg-white">{{ $ageGroups->links() }}</div>
    @endif
</div>
@endsection
