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
                <label class="form-label small fw-semibold">Age Group</label>
                <select name="age_group" class="form-select form-select-sm">
                    <option value="">All Age Groups</option>
                    @foreach($ageGroups as $ag)
                        <option value="{{ $ag->name }}" {{ str_replace(' ', '+', request('age_group')) == $ag->name ? 'selected' : '' }}>{{ $ag->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Travel Type</label>
                <select name="travel_type" class="form-select form-select-sm">
                    <option value="">All Travel Types</option>
                    @foreach($travelTypes as $tt)
                        <option value="{{ $tt->name }}" {{ request('travel_type') == $tt->name ? 'selected' : '' }}>{{ $tt->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Budget</label>
                <select name="budget" class="form-select form-select-sm">
                    <option value="">All Budgets</option>
                    @foreach($budgetCategories as $bc)
                        <option value="{{ $bc->name }}" {{ request('budget') == $bc->name ? 'selected' : '' }}>{{ $bc->name }}</option>
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
            @if(request()->hasAny(['search','country_id','purpose_id','age_group','travel_type','budget','date_from','date_to']))
            <div class="col-md-1">
                <a href="{{ route('tourism-data.index') }}" class="btn btn-secondary btn-sm w-100"><i class="bi bi-x-lg"></i></a>
            </div>
            @endif
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
                        <th>Country of Origin</th>
                        <th>City Visited</th>
                        <th>Purpose</th>
                        <th>Visit Date</th>
                        <th width="150">Actions</th>
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
                        <td>{{ $record->visit_date ? $record->visit_date->format('d M Y') : '-' }}</td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm" title="View Details" data-bs-toggle="modal" data-bs-target="#viewModal"
                                data-visitor="{{ $record->visitor_name ?? 'Anonymous' }}"
                                data-country="{{ $record->country->name }}"
                                data-city="{{ $record->city_visited }}"
                                data-purpose="{{ $record->purpose->name }}"
                                data-date="{{ $record->visit_date ? $record->visit_date->format('d M Y') : '-' }}"
                                data-month="{{ $record->month && $record->month >= 1 && $record->month <= 12 ? date('F', mktime(0, 0, 0, $record->month, 1)) : '-' }}"
                                data-age-group="{{ $record->age_group ?? '-' }}"
                                data-travel-type="{{ $record->travel_type ?? '-' }}"
                                data-budget="{{ $record->budget ?? '-' }}"
                                data-duration="{{ $record->duration ? $record->duration . ' days' : '-' }}"
                                data-satisfaction="{{ $record->satisfaction ? $record->satisfaction . '/5' : '-' }}"
                                data-previous-visits="{{ $record->previous_visits ?? '-' }}"
                                data-spending="{{ $record->spending ? '$' . number_format($record->spending, 2) : '-' }}"
                                data-will-return="{{ !is_null($record->will_return) ? ($record->will_return ? 'Yes' : 'No') : '-' }}"
                                data-feedback="{{ $record->feedback ?? '-' }}"
                                data-recorded-by="{{ $record->creator->name }}">
                                <i class="bi bi-eye"></i>
                            </button>
                            @if(auth()->user()->isAdmin())
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
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No records found.</td>
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

{{-- View Details Modal --}}
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewModalLabel"><i class="bi bi-eye"></i> Tourism Record Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr><th class="text-muted" width="40%">Visitor Name</th><td id="modal-visitor"></td></tr>
                            <tr><th class="text-muted">Country of Origin</th><td id="modal-country"></td></tr>
                            <tr><th class="text-muted">City Visited</th><td id="modal-city"></td></tr>
                            <tr><th class="text-muted">Purpose</th><td id="modal-purpose"></td></tr>
                            <tr><th class="text-muted">Visit Date</th><td id="modal-date"></td></tr>
                            <tr><th class="text-muted">Month</th><td id="modal-month"></td></tr>
                            <tr><th class="text-muted">Age Group</th><td id="modal-age-group"></td></tr>
                            <tr><th class="text-muted">Recorded By</th><td id="modal-recorded-by"></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr><th class="text-muted" width="40%">Travel Type</th><td id="modal-travel-type"></td></tr>
                            <tr><th class="text-muted">Budget</th><td id="modal-budget"></td></tr>
                            <tr><th class="text-muted">Duration</th><td id="modal-duration"></td></tr>
                            <tr><th class="text-muted">Satisfaction</th><td id="modal-satisfaction"></td></tr>
                            <tr><th class="text-muted">Previous Visits</th><td id="modal-previous-visits"></td></tr>
                            <tr><th class="text-muted">Spending</th><td id="modal-spending"></td></tr>
                            <tr><th class="text-muted">Will Return</th><td id="modal-will-return"></td></tr>
                        </table>
                    </div>
                </div>
                <div class="border-top pt-2 mt-2">
                    <strong class="text-muted">Feedback:</strong>
                    <p id="modal-feedback" class="mb-0 mt-1"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('viewModal').addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    document.getElementById('modal-visitor').textContent = btn.dataset.visitor;
    document.getElementById('modal-country').textContent = btn.dataset.country;
    document.getElementById('modal-city').textContent = btn.dataset.city;
    document.getElementById('modal-purpose').textContent = btn.dataset.purpose;
    document.getElementById('modal-date').textContent = btn.dataset.date;
    document.getElementById('modal-month').textContent = btn.dataset.month;
    document.getElementById('modal-age-group').textContent = btn.dataset.ageGroup;
    document.getElementById('modal-travel-type').textContent = btn.dataset.travelType;
    document.getElementById('modal-budget').textContent = btn.dataset.budget;
    document.getElementById('modal-duration').textContent = btn.dataset.duration;
    document.getElementById('modal-satisfaction').textContent = btn.dataset.satisfaction;
    document.getElementById('modal-previous-visits').textContent = btn.dataset.previousVisits;
    document.getElementById('modal-spending').textContent = btn.dataset.spending;
    document.getElementById('modal-will-return').textContent = btn.dataset.willReturn;
    document.getElementById('modal-feedback').textContent = btn.dataset.feedback;
    document.getElementById('modal-recorded-by').textContent = btn.dataset.recordedBy;
});
</script>
@endpush
