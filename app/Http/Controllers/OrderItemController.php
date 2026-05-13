<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderItemController extends Controller
{
    public function index()
    {
        try {
            $orderItems = OrderItem::with(['order', 'service', 'sparePart'])->paginate(10);
            return response()->json([
                'success' => true,
                'message' => 'Data order items retrieved successfully',
                'data' => $orderItems
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
                'service_id' => 'nullable|exists:services,id',
                'spare_part_id' => 'nullable|exists:spare_parts,id',
                'quantity' => 'required|integer|min:1',
                'unit_price' => 'required|numeric',
                'subtotal' => 'required|numeric',
                'notes' => 'nullable|string',
            ]);

            $orderItem = OrderItem::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Order item created successfully',
                'data' => $orderItem
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(OrderItem $orderItem)
    {
        try {
            $orderItem->load(['order', 'service', 'sparePart']);
            return response()->json([
                'success' => true,
                'message' => 'Order item retrieved successfully',
                'data' => $orderItem
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, OrderItem $orderItem)
    {
        try {
            $validated = $request->validate([
                'order_id' => 'sometimes|exists:orders,id',
                'service_id' => 'nullable|exists:services,id',
                'spare_part_id' => 'nullable|exists:spare_parts,id',
                'quantity' => 'sometimes|integer|min:1',
                'unit_price' => 'sometimes|numeric',
                'subtotal' => 'sometimes|numeric',
                'notes' => 'nullable|string',
            ]);

            $orderItem->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Order item updated successfully',
                'data' => $orderItem
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(OrderItem $orderItem)
    {
        try {
            $orderItem->delete();
            return response()->json([
                'success' => true,
                'message' => 'Order item deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}