<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanStaffController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['user', 'book'])->orderByDesc('borrowed_at')->get();

        return response()->json([
            'data' => $loans
        ]);
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:borrowed,returned,cancelled',
        ]);

        $loan->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'message' => 'Loan status updated by staff',
            'data' => [
                'id' => $loan->id,
                'status' => $loan->status,
            ]
        ]);
    }
}
