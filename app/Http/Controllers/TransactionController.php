<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    public function index()
    {
        try {
            $transactions = Transaction::with('order')->paginate(10);
            return response()->json([
                'success' => true,
                'message' => 'Data transactions retrieved successfully',
                'data' => $transactions
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'transaction_number' => 'required|string|unique:transactions',
                'amount' => 'required|numeric',
                'payment_method' => 'required|in:tunai,transfer,kartu_kredit,cicilan',
                'status' => 'required|in:pending,completed,failed,refunded',
                'reference_number' => 'nullable|string|unique:transactions',
                'notes' => 'nullable|string',
                'paid_at' => 'nullable|date',
            ]);

            $transaction = Transaction::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully',
                'data' => $transaction
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Transaction $transaction)
    {
        try {
            $transaction->load('order');
            return response()->json([
                'success' => true,
                'message' => 'Transaction retrieved successfully',
                'data' => $transaction
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, Transaction $transaction)
    {
        try {
            $validated = $request->validate([
                'order_id' => 'sometimes|exists:orders,id',
                'transaction_number' => 'sometimes|string|unique:transactions,transaction_number,' . $transaction->id,
                'amount' => 'sometimes|numeric',
                'payment_method' => 'sometimes|in:tunai,transfer,kartu_kredit,cicilan',
                'status' => 'sometimes|in:pending,completed,failed,refunded',
                'reference_number' => 'nullable|string|unique:transactions,reference_number,' . $transaction->id,
                'notes' => 'nullable|string',
                'paid_at' => 'nullable|date',
            ]);

            $transaction->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Transaction updated successfully',
                'data' => $transaction
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Transaction $transaction)
    {
        try {
            $transaction->delete();
            return response()->json([
                'success' => true,
                'message' => 'Transaction deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}