@extends('layouts.app')

@section('title', 'Visit Purposes')
@section('page-title', 'Visit Purposes Management')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0">All Visit Purposes</h6>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('visit-purposes.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Add Purpose
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
                @forelse($purposes as $purpose)
                <tr>
                    <td>{{ $loop->iteration + ($purposes->currentPage() - 1) * $purposes->perPage() }}</td>
                    <td>{{ $purpose->name }}</td>
                    <td><span class="badge bg-primary">{{ $purpose->tourism_data_count }}</span></td>
                    <td>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('visit-purposes.edit', $purpose) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('visit-purposes.destroy', $purpose) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this purpose?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">No visit purposes added yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($purposes->hasPages())
    <div class="card-footer bg-white">{{ $purposes->links() }}</div>
    @endif
</div>
@endsection
