@extends('layouts.app')

@section('title', 'Travel Types')
@section('page-title', 'Travel Types Management')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0">All Travel Types</h6>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('travel-types.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Add Travel Type
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
                @forelse($travelTypes as $travelType)
                <tr>
                    <td>{{ $loop->iteration + ($travelTypes->currentPage() - 1) * $travelTypes->perPage() }}</td>
                    <td>{{ $travelType->name }}</td>
                    <td><a href="{{ URL::to('tourism-data?travel_type_id=' . $travelType->id) }}" class="badge rounded-pill bg-primary text-decoration-none">{{ $travelType->tourism_data_count }}</a></td>
                    <td>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('travel-types.edit', $travelType) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('travel-types.destroy', $travelType) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this travel type?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">No travel types added yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($travelTypes->hasPages())
    <div class="card-footer bg-white">{{ $travelTypes->links() }}</div>
    @endif
</div>
@endsection
