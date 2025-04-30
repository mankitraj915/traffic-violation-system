@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add New Violation</h2>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('violations.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="license_plate" class="form-label">License Plate</label>
                            <input type="text" name="license_plate" id="license_plate" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="violation_type" class="form-label">Violation Type</label>
                            <input type="text" name="violation_type" id="violation_type" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" name="location" id="location" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_time" class="form-label">Date and Time</label>
                            <input type="datetime-local" name="date_time" id="date_time" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="fine_amount" class="form-label">Fine Amount</label>
                            <input type="number" step="0.01" name="fine_amount" id="fine_amount" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="evidence" class="form-label">Evidence (Photo)</label>
                            <input type="file" name="evidence" id="evidence" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection