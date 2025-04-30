@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Violation Details</h2>
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if ($violation)
                        <div class="mb-3">
                            <strong>License Plate:</strong> {{ $violation->license_plate }}<br>
                            <strong>Violation Type:</strong> {{ $violation->violation_type }}<br>
                            <strong>Location:</strong> {{ $violation->location }}<br>
                            <strong>Date and Time:</strong> {{ $violation->date_time }}<br>
                            <strong>Fine Amount:</strong> ${{ $violation->fine_amount }}<br>
                            <strong>Status:</strong> {{ $violation->status }}<br>
                            <strong>Dispute Reason:</strong> {{ $violation->dispute_reason ?? 'N/A' }}<br>
                            @if ($violation->evidence)
                                <strong>Evidence:</strong> 
                                <a href="{{ asset('storage/' . $violation->evidence) }}" target="_blank">View Photo</a><br>
                                <small>URL: {{ asset('storage/' . $violation->evidence) }}</small><br>
                            @endif
                        </div>
                        @if ($violation->status === 'pending')
                            <a href="{{ route('violations.edit', $violation) }}" class="btn btn-warning btn-sm">Dispute</a>
                            <a href="{{ route('payments.create', $violation) }}" class="btn btn-success btn-sm">Pay</a>
                        @endif
                        <a href="{{ route('violations.index') }}" class="btn btn-secondary btn-sm">Back</a>
                    @else
                        <p>No violation details found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection