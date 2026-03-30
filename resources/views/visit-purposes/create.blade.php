@extends('layouts.app')

@section('title', 'Add Visit Purpose')
@section('page-title', 'Add Visit Purpose')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-plus-circle"></i> New Visit Purpose</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('visit-purposes.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Purpose Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g. Tourism, Business, Education">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Save</button>
                        <a href="{{ route('visit-purposes.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
