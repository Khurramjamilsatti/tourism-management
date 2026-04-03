@extends('layouts.app')

@section('title', 'Add Tourism Data')
@section('page-title', 'Add Tourism Data')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-plus-circle"></i> New Tourism Data Entry</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('tourism-data.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="visitor_name" class="form-label fw-semibold">Visitor Name <small class="text-muted">(optional)</small></label>
                            <input type="text" class="form-control @error('visitor_name') is-invalid @enderror" id="visitor_name" name="visitor_name" value="{{ old('visitor_name') }}" placeholder="Enter visitor name">
                            @error('visitor_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="country_id" class="form-label fw-semibold">Country of Origin <span class="text-danger">*</span></label>
                            <select class="form-select @error('country_id') is-invalid @enderror" id="country_id" name="country_id" required>
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('country_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city_visited" class="form-label fw-semibold">City Visited <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('city_visited') is-invalid @enderror" id="city_visited" name="city_visited" value="{{ old('city_visited') }}" required placeholder="Enter city name">
                            @error('city_visited')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="purpose_id" class="form-label fw-semibold">Purpose of Visit <span class="text-danger">*</span></label>
                            <select class="form-select @error('purpose_id') is-invalid @enderror" id="purpose_id" name="purpose_id" required>
                                <option value="">Select Purpose</option>
                                @foreach($purposes as $purpose)
                                    <option value="{{ $purpose->id }}" {{ old('purpose_id') == $purpose->id ? 'selected' : '' }}>{{ $purpose->name }}</option>
                                @endforeach
                            </select>
                            @error('purpose_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="visit_date" class="form-label fw-semibold">Visit Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('visit_date') is-invalid @enderror" id="visit_date" name="visit_date" value="{{ old('visit_date', date('Y-m-d')) }}" required>
                        @error('visit_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="month" class="form-label fw-semibold">Month</label>
                            <select class="form-select @error('month') is-invalid @enderror" id="month" name="month">
                                <option value="">Select Month</option>
                                @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $i => $m)
                                    <option value="{{ $i + 1 }}" {{ old('month') == ($i + 1) ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                            @error('month')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="age_group" class="form-label fw-semibold">Age Group</label>
                            <select class="form-select @error('age_group') is-invalid @enderror" id="age_group" name="age_group">
                                <option value="">Select Age Group</option>
                                @foreach($ageGroups as $group)
                                    <option value="{{ $group->name }}" {{ old('age_group') == $group->name ? 'selected' : '' }}>{{ $group->name }}</option>
                                @endforeach
                            </select>
                            @error('age_group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="travel_type" class="form-label fw-semibold">Travel Type</label>
                            <select class="form-select @error('travel_type') is-invalid @enderror" id="travel_type" name="travel_type">
                                <option value="">Select Travel Type</option>
                                @foreach($travelTypes as $type)
                                    <option value="{{ $type->name }}" {{ old('travel_type') == $type->name ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('travel_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="budget" class="form-label fw-semibold">Budget</label>
                            <select class="form-select @error('budget') is-invalid @enderror" id="budget" name="budget">
                                <option value="">Select Budget</option>
                                @foreach($budgetCategories as $b)
                                    <option value="{{ $b->name }}" {{ old('budget') == $b->name ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                            @error('budget')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="duration" class="form-label fw-semibold">Duration (days)</label>
                            <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration') }}" min="1" placeholder="Number of days">
                            @error('duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="satisfaction" class="form-label fw-semibold">Satisfaction (1-5)</label>
                            <select class="form-select @error('satisfaction') is-invalid @enderror" id="satisfaction" name="satisfaction">
                                <option value="">Select Rating</option>
                                @for($s = 1; $s <= 5; $s++)
                                    <option value="{{ $s }}" {{ old('satisfaction') == $s ? 'selected' : '' }}>{{ $s }} - {{ ['Very Poor','Poor','Average','Good','Excellent'][$s-1] }}</option>
                                @endfor
                            </select>
                            @error('satisfaction')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="previous_visits" class="form-label fw-semibold">Previous Visits</label>
                            <input type="number" class="form-control @error('previous_visits') is-invalid @enderror" id="previous_visits" name="previous_visits" value="{{ old('previous_visits') }}" min="0" placeholder="0">
                            @error('previous_visits')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="spending" class="form-label fw-semibold">Spending ($)</label>
                            <input type="number" step="0.01" class="form-control @error('spending') is-invalid @enderror" id="spending" name="spending" value="{{ old('spending') }}" min="0" placeholder="0.00">
                            @error('spending')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input @error('will_return') is-invalid @enderror" type="checkbox" id="will_return" name="will_return" value="1" {{ old('will_return') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="will_return">Will Return?</label>
                                @error('will_return')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="feedback" class="form-label fw-semibold">Feedback/Comments <small class="text-muted">(optional)</small></label>
                        <textarea class="form-control @error('feedback') is-invalid @enderror" id="feedback" name="feedback" rows="3" placeholder="Enter visitor feedback or comments">{{ old('feedback') }}</textarea>
                        @error('feedback')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Weather Widget --}}
                    <div class="card bg-light mb-3" id="weather-widget" style="display:none;">
                        <div class="card-body py-2">
                            <h6 class="mb-1"><i class="bi bi-cloud-sun"></i> Current Weather</h6>
                            <div id="weather-info"></div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save Record</button>
                        <a href="{{ route('tourism-data.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="button" class="btn btn-outline-info ms-auto" onclick="fetchWeather()"><i class="bi bi-cloud-sun"></i> Check Weather</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function fetchWeather() {
    const city = document.getElementById('city_visited').value;
    if (!city) { alert('Please enter a city name first.'); return; }

    const widget = document.getElementById('weather-widget');
    const info = document.getElementById('weather-info');
    info.innerHTML = '<small class="text-muted">Loading weather data...</small>';
    widget.style.display = 'block';

    fetch(`{{ route('weather') }}?city=${encodeURIComponent(city)}`)
        .then(r => r.json())
        .then(data => {
            if (data.error) {
                info.innerHTML = `<small class="text-danger">${data.error}</small>`;
            } else {
                info.innerHTML = `
                    <div class="d-flex gap-3 align-items-center">
                        <div><strong>${data.city}, ${data.country}</strong></div>
                        <div><i class="bi bi-thermometer-half"></i> ${data.temperature}°C</div>
                        <div><i class="bi bi-droplet"></i> ${data.humidity}%</div>
                        <div><i class="bi bi-wind"></i> ${data.wind_speed} km/h</div>
                        <div><span class="badge bg-secondary">${data.weather_description}</span></div>
                    </div>`;
            }
        })
        .catch(() => { info.innerHTML = '<small class="text-danger">Failed to fetch weather data.</small>'; });
}
</script>
@endpush
