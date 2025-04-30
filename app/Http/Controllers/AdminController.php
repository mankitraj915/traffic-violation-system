<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function disputes()
    {
        $disputes = Violation::where('status', 'disputed')->get();
        return view('admin.disputes', compact('disputes'));
    }

    public function resolve(Request $request, Violation $violation)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        if ($violation->status !== 'disputed') {
            return redirect()->route('admin.disputes')->with('error', 'Only disputed violations can be resolved.');
        }

        $newStatus = $request->action === 'approve' ? 'paid' : 'pending';
        $violation->update(['status' => $newStatus]);

        $message = $request->action === 'approve' ? 'Dispute approved and violation marked as paid.' : 'Dispute rejected and violation set back to pending.';
        return redirect()->route('admin.disputes')->with('success', $message);
    }
}