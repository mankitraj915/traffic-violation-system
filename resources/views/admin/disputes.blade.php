<!DOCTYPE html>
<html>
<head>
    <title>Review Disputes</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="max-w-6xl mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Review Disputes</h1>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if ($disputes->isEmpty())
            <p class="text-gray-500 text-lg">No disputed violations found.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 text-sm uppercase tracking-wider">
                            <th class="border px-6 py-3 text-left">ID</th>
                            <th class="border px-6 py-3 text-left">Dispute Reason</th>
                            <th class="border px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($disputes as $dispute)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="border px-6 py-4 text-gray-700">{{ $dispute->id }}</td>
                                <td class="border px-6 py-4 text-gray-700">{{ $dispute->dispute_reason ?? 'No reason provided' }}</td>
                                <td class="border px-6 py-4">
                                    <form action="{{ route('admin.disputes.resolve', $dispute->id) }}" method="POST" class="flex space-x-2">
                                        @csrf
                                        <button type="submit" name="action" value="approve" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Approve</button>
                                        <button type="submit" name="action" value="reject" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</body>
</html>