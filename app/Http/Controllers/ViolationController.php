<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Attributes\Middleware;

#[Middleware('auth')]
class ViolationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = Violation::query();

        if ($status) {
            $query->where('status', $status);
        }

        $violations = $query->get();
        return view('violations.index', compact('violations'));
    }

    public function create()
    {
        return view('violations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'license_plate' => 'required|string|max:255',
            'violation_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'date_time' => 'required|date',
            'fine_amount' => 'required|numeric|min:0',
            'evidence' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('evidence')) {
            $data['evidence'] = $request->file('evidence')->store('evidence', 'public');
        }
        $data['status'] = 'pending';
        $data['user_id'] = Auth::id();

        Violation::create($data);
        return redirect()->route('violations.index')->with('success', 'Violation created successfully.');
    }

    public function show(Violation $violation)
    {
        return view('violations.show', compact('violation'));
    }

    public function edit(Violation $violation)
    {
        if ($violation->status !== 'pending') {
            return redirect()->route('violations.index')->with('error', 'Only pending violations can be disputed.');
        }
        return view('violations.edit', compact('violation'));
    }

    public function update(Request $request, Violation $violation)
    {
        if ($violation->status !== 'pending') {
            return redirect()->route('violations.index')->with('error', 'Only pending violations can be disputed.');
        }

        $request->validate([
            'dispute_reason' => 'required|string|max:1000',
            'status' => 'required|in:disputed',
        ]);

        $violation->update([
            'dispute_reason' => $request->dispute_reason,
            'status' => $request->status,
        ]);

        return redirect()->route('violations.index')->with('success', 'Violation disputed successfully.');
    }

    public function destroy(Violation $violation)
    {
        $violation->delete();
        return redirect()->route('violations.index')->with('success', 'Violation deleted successfully.');
    }
}