<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::with(['bengkel', 'vehicle', 'mechanic', 'orderItems', 'transactions', 'ratings'])->paginate(10);
            return response()->json([
                'success' => true,
                'message' => 'Data orders retrieved successfully',
                'data' => $orders
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
                'bengkel_id' => 'required|exists:bengkels,id',
                'vehicle_id' => 'required|exists:vehicles,id',
                'mechanic_id' => 'nullable|exists:mechanics,id',
                'order_number' => 'required|string|unique:orders',
                'description' => 'required|string',
                'total_price' => 'required|numeric',
                'discount' => 'nullable|numeric',
                'final_price' => 'required|numeric',
                'status' => 'required|in:pending,in_progress,completed,cancelled',
                'priority' => 'required|in:low,medium,high,urgent',
                'started_at' => 'nullable|date',
                'completed_at' => 'nullable|date',
                'notes' => 'nullable|string',
            ]);

            $order = Order::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Order $order)
    {
        try {
            $order->load(['bengkel', 'vehicle', 'mechanic', 'orderItems', 'transactions', 'ratings']);
            return response()->json([
                'success' => true,
                'message' => 'Order retrieved successfully',
                'data' => $order
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, Order $order)
    {
        try {
            $validated = $request->validate([
                'bengkel_id' => 'sometimes|exists:bengkels,id',
                'vehicle_id' => 'sometimes|exists:vehicles,id',
                'mechanic_id' => 'nullable|exists:mechanics,id',
                'order_number' => 'sometimes|string|unique:orders,order_number,' . $order->id,
                'description' => 'sometimes|string',
                'total_price' => 'sometimes|numeric',
                'discount' => 'nullable|numeric',
                'final_price' => 'sometimes|numeric',
                'status' => 'sometimes|in:pending,in_progress,completed,cancelled',
                'priority' => 'sometimes|in:low,medium,high,urgent',
                'started_at' => 'nullable|date',
                'completed_at' => 'nullable|date',
                'notes' => 'nullable|string',
            ]);

            $order->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
                'data' => $order
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Order $order)
    {
        try {
            $order->delete();
            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}