@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Violations</h2>
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="mb-3">
                        <form action="{{ route('violations.index') }}" method="GET">
                            <label for="status" class="form-label">Filter by Status:</label>
                            <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                                <option value="">All</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="disputed" {{ request('status') === 'disputed' ? 'selected' : '' }}>Disputed</option>
                            </select>
                        </form>
                    </div>
                    <a href="{{ route('violations.create') }}" class="btn btn-primary mb-3">Create Violation</a>
                    @if ($violations->isEmpty())
                        <p>No violations found.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>License Plate</th>
                                    <th>Violation Type</th>
                                    <th>Location</th>
                                    <th>Date and Time</th>
                                    <th>Fine Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($violations as $violation)
                                    <tr>
                                        <td>{{ $violation->license_plate }}</td>
                                        <td>{{ $violation->violation_type }}</td>
                                        <td>{{ $violation->location }}</td>
                                        <td>{{ $violation->date_time }}</td>
                                        <td>${{ $violation->fine_amount }}</td>
                                        <td>{{ $violation->status }}</td>
                                        <td>
                                            <a href="{{ route('violations.show', $violation) }}" class="btn btn-info btn-sm">View</a>
                                            @if ($violation->status === 'pending')
                                                <a href="{{ route('violations.edit', $violation) }}" class="btn btn-warning btn-sm">Dispute</a>
                                                <a href="{{ route('payments.create', $violation) }}" class="btn btn-success btn-sm">Pay</a>
                                            @endif
                                            <form action="{{ route('violations.destroy', $violation) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection