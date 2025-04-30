<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Attributes\Middleware;

#[Middleware('auth')]
class PaymentController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $payments = Payment::where('user_id', $userId)->get();
        \Log::info('Payments Index: User ID - ' . $userId . ', Payments - ' . $payments->toJson());
        return view('payments.index', compact('payments'));
    }

    public function create(Violation $violation)
    {
        \Log::info('Payment Create: Violation ID - ' . $violation->id);
        return view('payments.create', compact('violation'));
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Payment Store: Starting payment process');
            \Log::info('Payment Store: Request Data - ' . json_encode($request->all()));

            // Validate the request
            \Log::info('Payment Store: Validating request');
            $validated = $request->validate([
                'violation_id' => 'required|exists:violations,id',
                'amount' => 'required|numeric|min:0',
            ]);
            \Log::info('Payment Store: Validation passed - ' . json_encode($validated));

            // Find the violation
            \Log::info('Payment Store: Finding violation with ID - ' . $request->violation_id);
            $violation = Violation::findOrFail($request->violation_id);
            \Log::info('Payment Store: Found Violation: ID - ' . $violation->id . ', Status - ' . $violation->status . ', Fine Amount - ' . $violation->fine_amount);

            // Check violation status
            if ($violation->status !== 'pending') {
                \Log::warning('Payment Store: Payment rejected - Violation status is ' . $violation->status);
                return redirect()->route('violations.index')->with('error', 'Cannot pay for a violation that is not pending.');
            }
            \Log::info('Payment Store: Violation status is pending, proceeding');

            // Verify user authentication
            $userId = Auth::id();
            if (!$userId) {
                \Log::error('Payment Store: User not authenticated');
                return redirect()->route('login')->with('error', 'Please log in to make a payment.');
            }
            \Log::info('Payment Store: User authenticated, ID - ' . $userId);

            // Create the payment
            \Log::info('Payment Store: Creating payment for User ID - ' . $userId);
            $payment = Payment::create([
                'user_id' => $userId,
                'violation_id' => $violation->id,
                'amount' => $request->amount,
                'payment_status' => 'completed', // Explicitly set payment_status
                'status' => 'completed', // Legacy field (if exists), align with payment_status
            ]);
            \Log::info('Payment Store: Payment created: ID - ' . $payment->id . ', User ID - ' . $payment->user_id . ', Amount - ' . $payment->amount . ', Payment Status - ' . $payment->payment_status);

            // Update violation status
            \Log::info('Payment Store: Updating violation status to paid');
            $violation->update(['status' => 'paid']);
            \Log::info('Payment Store: Violation updated to paid: ID - ' . $violation->id);

            // Redirect to payments index
            \Log::info('Payment Store: Redirecting to payments.index');
            return redirect()->route('payments.index')->with('success', 'Payment created successfully.');
        } catch (\Exception $e) {
            \Log::error('Payment Store: Failed to create payment: ' . $e->getMessage());
            \Log::error('Payment Store: Exception Stack Trace - ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Failed to create payment: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Payment $payment)
    {
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $payment->update($request->all());
        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }
}