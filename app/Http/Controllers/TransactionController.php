<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class TransactionController extends Controller
{
    #[OA\Get(path: "/api/transactions", tags: ["Transaction"], summary: "Get semua transactions", security: [["token" => []]], responses: [new OA\Response(response: 200, description: "Data transactions retrieved successfully")])]
    public function index()
    {
        try {
            $transactions = Transaction::with("order")->paginate(10);
            return response()->json(["success" => true, "message" => "Data transactions retrieved successfully", "data" => $transactions], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Post(path: "/api/transactions", tags: ["Transaction"], summary: "Tambah transaction baru", security: [["token" => []]], responses: [new OA\Response(response: 201, description: "Transaction created successfully")])]
    public function store(Request $request)
    {
        try {
            $validated = $request->validate(["order_id" => "required|exists:orders,id", "transaction_number" => "required|string|unique:transactions", "amount" => "required|numeric", "payment_method" => "required|in:tunai,transfer,kartu_kredit,cicilan", "status" => "required|in:pending,completed,failed,refunded", "reference_number" => "nullable|string|unique:transactions", "notes" => "nullable|string", "paid_at" => "nullable|date"]);
            $transaction = Transaction::create($validated);
            return response()->json(["success" => true, "message" => "Transaction created successfully", "data" => $transaction], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Get(path: "/api/transactions/{id}", tags: ["Transaction"], summary: "Get transaction by ID", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "Transaction retrieved successfully")])]
    public function show(Transaction $transaction)
    {
        try {
            $transaction->load("order");
            return response()->json(["success" => true, "message" => "Transaction retrieved successfully", "data" => $transaction], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Put(path: "/api/transactions/{id}", tags: ["Transaction"], summary: "Update transaction", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "Transaction updated successfully")])]
    public function update(Request $request, Transaction $transaction)
    {
        try {
            $validated = $request->validate(["amount" => "sometimes|numeric", "payment_method" => "sometimes|in:tunai,transfer,kartu_kredit,cicilan", "status" => "sometimes|in:pending,completed,failed,refunded"]);
            $transaction->update($validated);
            return response()->json(["success" => true, "message" => "Transaction updated successfully", "data" => $transaction], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Delete(path: "/api/transactions/{id}", tags: ["Transaction"], summary: "Hapus transaction", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "Transaction deleted successfully")])]
    public function destroy(Transaction $transaction)
    {
        try {
            $transaction->delete();
            return response()->json(["success" => true, "message" => "Transaction deleted successfully"], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}