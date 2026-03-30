@extends('layouts.app')

@section('title', 'Add Tourism Data')
@section('page-title', 'Add Tourism Data')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
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
