@extends('layouts.app')

@section('title', 'Countries')
@section('page-title', 'Countries Management')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0">All Countries</h6>
        <a href="{{ route('countries.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Add Country
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Records</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($countries as $country)
                <tr>
                    <td>{{ $loop->iteration + ($countries->currentPage() - 1) * $countries->perPage() }}</td>
                    <td>{{ $country->name }}</td>
                    <td>{{ $country->code ?? '-' }}</td>
                    <td><span class="badge bg-primary">{{ $country->tourism_data_count }}</span></td>
                    <td>
                        <a href="{{ route('countries.edit', $country) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('countries.destroy', $country) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this country?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No countries added yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($countries->hasPages())
    <div class="card-footer bg-white">{{ $countries->links() }}</div>
    @endif
</div>
@endsection
