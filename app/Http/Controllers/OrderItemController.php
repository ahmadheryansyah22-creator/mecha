<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class OrderItemController extends Controller
{
    #[OA\Get(path: "/api/order-items", tags: ["OrderItem"], summary: "Get semua order items", security: [["token" => []]], responses: [new OA\Response(response: 200, description: "Data order items retrieved successfully")])]
    public function index()
    {
        try {
            $orderItems = OrderItem::with(["order", "service", "sparePart"])->paginate(10);
            return response()->json(["success" => true, "message" => "Data order items retrieved successfully", "data" => $orderItems], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Post(path: "/api/order-items", tags: ["OrderItem"], summary: "Tambah order item baru", security: [["token" => []]], responses: [new OA\Response(response: 201, description: "Order item created successfully")])]
    public function store(Request $request)
    {
        try {
            $validated = $request->validate(["order_id" => "required|exists:orders,id", "service_id" => "nullable|exists:services,id", "spare_part_id" => "nullable|exists:spare_parts,id", "quantity" => "required|integer|min:1", "unit_price" => "required|numeric", "subtotal" => "required|numeric", "notes" => "nullable|string"]);
            $orderItem = OrderItem::create($validated);
            return response()->json(["success" => true, "message" => "Order item created successfully", "data" => $orderItem], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Get(path: "/api/order-items/{id}", tags: ["OrderItem"], summary: "Get order item by ID", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "Order item retrieved successfully")])]
    public function show(OrderItem $orderItem)
    {
        try {
            $orderItem->load(["order", "service", "sparePart"]);
            return response()->json(["success" => true, "message" => "Order item retrieved successfully", "data" => $orderItem], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Put(path: "/api/order-items/{id}", tags: ["OrderItem"], summary: "Update order item", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "Order item updated successfully")])]
    public function update(Request $request, OrderItem $orderItem)
    {
        try {
            $validated = $request->validate(["order_id" => "sometimes|exists:orders,id", "quantity" => "sometimes|integer|min:1", "unit_price" => "sometimes|numeric", "subtotal" => "sometimes|numeric"]);
            $orderItem->update($validated);
            return response()->json(["success" => true, "message" => "Order item updated successfully", "data" => $orderItem], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Delete(path: "/api/order-items/{id}", tags: ["OrderItem"], summary: "Hapus order item", security: [["token" => []]], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))], responses: [new OA\Response(response: 200, description: "Order item deleted successfully")])]
    public function destroy(OrderItem $orderItem)
    {
        try {
            $orderItem->delete();
            return response()->json(["success" => true, "message" => "Order item deleted successfully"], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}