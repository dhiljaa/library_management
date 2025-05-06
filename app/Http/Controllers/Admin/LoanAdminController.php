<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use App\Http\Resources\LoanResource;

class LoanAdminController extends Controller
{
    // 📚 GET /api/admin/loans
    public function index()
    {
        try {
            $loans = Loan::with(['user', 'book'])->orderByDesc('borrowed_at')->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Loan list retrieved successfully',
                'data' => LoanResource::collection($loans),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch loans',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // 🔄 PATCH /api/admin/loans/{id}
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:borrowed,returned,cancelled',
            ]);

            $loan = Loan::findOrFail($id);
            $loan->update(['status' => $validated['status']]);

            return response()->json([
                'status' => 'success',
                'message' => 'Loan status updated successfully',
                'data' => new LoanResource($loan),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update loan status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
