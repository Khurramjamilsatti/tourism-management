@extends('layouts.app')

@section('title', 'Tourism Data')
@section('page-title', 'Tourism Data Management')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('tourism-data.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Name or city..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Country</label>
                <select name="country_id" class="form-select form-select-sm">
                    <option value="">All Countries</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" {{ request('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Purpose</label>
                <select name="purpose_id" class="form-select form-select-sm">
                    <option value="">All Purposes</option>
                    @foreach($purposes as $purpose)
                        <option value="{{ $purpose->id }}" {{ request('purpose_id') == $purpose->id ? 'selected' : '' }}>{{ $purpose->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Date From</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Date To</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0">All Records ({{ $records->total() }})</h6>
        <a href="{{ route('tourism-data.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Add New Record
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Visitor Name</th>
                        <th>Country</th>
                        <th>City</th>
                        <th>Purpose</th>
                        <th>Visit Date</th>
                        <th>Recorded By</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                    <tr>
                        <td>{{ $loop->iteration + ($records->currentPage() - 1) * $records->perPage() }}</td>
                        <td>{{ $record->visitor_name ?? 'Anonymous' }}</td>
                        <td>{{ $record->country->name }}</td>
                        <td>{{ $record->city_visited }}</td>
                        <td><span class="badge bg-info">{{ $record->purpose->name }}</span></td>
                        <td>{{ $record->visit_date->format('d M Y') }}</td>
                        <td>{{ $record->creator->name }}</td>
                        <td>
                            <a href="{{ route('tourism-data.edit', $record) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('tourism-data.destroy', $record) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($records->hasPages())
    <div class="card-footer bg-white">
        {{ $records->links() }}
    </div>
    @endif
</div>
@endsection
