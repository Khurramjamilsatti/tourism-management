@extends('layouts.app')

@section('title', 'Countries')
@section('page-title', 'Countries Management')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('countries.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Search Country</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Country name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i></button>
            </div>
            @if(request('search'))
            <div class="col-md-1">
                <a href="{{ route('countries.index') }}" class="btn btn-secondary btn-sm w-100"><i class="bi bi-x-lg"></i></a>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0">All Countries</h6>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('countries.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Add Country
        </a>
        @endif
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
                    <td><a href="{{ URL::to('tourism-data?country_id=' . $country->id) }}" class="badge rounded-pill bg-primary text-decoration-none">{{ $country->tourism_data_count }}</a></td>
                    <td>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('countries.edit', $country) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('countries.destroy', $country) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this country?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                        @else
                        <span class="badge bg-light text-muted border" style="font-size:.7rem"><i class="bi bi-lock"></i> View only</span>
                        @endif
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
