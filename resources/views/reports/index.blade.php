@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Generate Reports')

@section('content')
<div class="card mb-4">
    <div class="card-header bg-white">
        <h6 class="mb-0"><i class="bi bi-funnel"></i> Report Filters</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('reports.generate') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Country</label>
                    <select name="country_id" class="form-select">
                        <option value="">All Countries</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ request('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Purpose</label>
                    <select name="purpose_id" class="form-select">
                        <option value="">All Purposes</option>
                        @foreach($purposes as $purpose)
                            <option value="{{ $purpose->id }}" {{ request('purpose_id') == $purpose->id ? 'selected' : '' }}>{{ $purpose->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-file-earmark-text"></i> Generate Report</button>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
            </div>
        </form>
    </div>
</div>

@if(isset($records))
{{-- Summary --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h4 class="text-primary">{{ $summary['total_records'] }}</h4>
                <small class="text-muted">Total Records</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h4 class="text-success">{{ $summary['countries'] }}</h4>
                <small class="text-muted">Countries</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h4 class="text-warning">{{ $summary['cities'] }}</h4>
                <small class="text-muted">Unique Cities</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <form method="GET" action="{{ route('reports.export') }}" class="d-inline">
                    <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                    <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                    <input type="hidden" name="country_id" value="{{ request('country_id') }}">
                    <input type="hidden" name="purpose_id" value="{{ request('purpose_id') }}">
                    <button type="submit" class="btn btn-success"><i class="bi bi-download"></i> Export CSV</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Report Table --}}
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Report Results</h6>
        <button class="btn btn-outline-secondary btn-sm" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Visitor Name</th>
                        <th>Country</th>
                        <th>City Visited</th>
                        <th>Purpose</th>
                        <th>Visit Date</th>
                        <th>Feedback</th>
                        <th>Recorded By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $index => $record)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $record->visitor_name ?? 'Anonymous' }}</td>
                        <td>{{ $record->country->name }}</td>
                        <td>{{ $record->city_visited }}</td>
                        <td><span class="badge bg-info">{{ $record->purpose->name }}</span></td>
                        <td>{{ $record->visit_date->format('d M Y') }}</td>
                        <td>{{ Str::limit($record->feedback, 50) ?? '-' }}</td>
                        <td>{{ $record->creator->name }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No records match the selected filters.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
