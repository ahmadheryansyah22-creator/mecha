<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $query = Order::with(["bengkel", "vehicle", "mechanic", "orderItems", "transactions", "ratings"]);

            // Filter berdasarkan role
            if ($user->role === 'mechanic') {
                // Mekanik hanya lihat order yang diassign ke dia
                $mechanic = Mechanic::where('email', $user->email)->first();
                if ($mechanic) $query->where('mechanic_id', $mechanic->id);
            } elseif ($user->role === 'customer') {
                // Customer hanya lihat order kendaraan miliknya
                $query->whereHas('vehicle', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }

            $orders = $query->latest()->paginate(10);
            return response()->json(["success" => true, "message" => "Data orders retrieved successfully", "data" => $orders], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                "bengkel_id" => "required|exists:bengkels,id",
                "vehicle_id" => "required|exists:vehicles,id",
                "mechanic_id" => "nullable|exists:mechanics,id",
                "order_number" => "required|string|unique:orders",
                "description" => "required|string",
                "total_price" => "required|numeric",
                "discount" => "nullable|numeric",
                "final_price" => "required|numeric",
                "mechanic_fee" => "nullable|numeric",
                "mechanic_status" => "nullable|in:waiting,accepted,rejected",
                "status" => "required|in:pending,in_progress,completed,cancelled",
                "priority" => "required|in:low,medium,high,urgent",
            ]);
            $order = Order::create($validated);
            return response()->json(["success" => true, "message" => "Order created successfully", "data" => $order], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Order $order)
    {
        try {
            $order->load(["bengkel", "vehicle", "mechanic", "orderItems", "transactions", "ratings"]);
            return response()->json(["success" => true, "message" => "Order retrieved successfully", "data" => $order], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, Order $order)
    {
        try {
            $validated = $request->validate([
                "bengkel_id" => "sometimes|exists:bengkels,id",
                "vehicle_id" => "sometimes|exists:vehicles,id",
                "mechanic_id" => "sometimes|nullable|exists:mechanics,id",
                "mechanic_fee" => "sometimes|nullable|numeric",
                "mechanic_status" => "sometimes|in:waiting,accepted,rejected",
                "mechanic_notes" => "sometimes|nullable|string",
                "status" => "sometimes|in:pending,in_progress,completed,cancelled",
                "priority" => "sometimes|in:low,medium,high,urgent",
                "notes" => "sometimes|nullable|string",
            ]);
            $order->update($validated);
            return response()->json(["success" => true, "message" => "Order updated successfully", "data" => $order->fresh(['mechanic', 'bengkel', 'vehicle'])], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Order $order)
    {
        try {
            $order->delete();
            return response()->json(["success" => true, "message" => "Order deleted successfully"], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
