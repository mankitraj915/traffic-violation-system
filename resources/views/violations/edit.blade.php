@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dispute Violation</h2>
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('violations.update', $violation) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="disputed">
                        <div class="mb-3">
                            <label for="dispute_reason" class="form-label">Dispute Reason</label>
                            <textarea name="dispute_reason" id="dispute_reason" class="form-control" required>{{ old('dispute_reason', $violation->dispute_reason) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-warning">Submit Dispute</button>
                        <a href="{{ route('violations.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection