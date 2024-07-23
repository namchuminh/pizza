<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\DetailOrder;


class DetailOrderController extends Controller
{
    public function show($order_id){
        $order = Order::find($order_id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if((auth()->user()->role == 'customer') && ($order->user_id != auth()->user()->id)){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $detailOrders = DetailOrder::find($order->id);

        return response()->json($detailOrders);
    }

    public function store(Request $request, $order_id){
        $order = Order::find($order_id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if((auth()->user()->role == 'customer') && ($order->user_id != auth()->user()->id)){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validatedData['order_id'] = $order_id;

        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string|max:255',
            'border' => 'required|string|max:255',
            'soles' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        // Tạo mới DetailOrder
        $detailOrder = DetailOrder::create($validatedData);

        return response()->json($detailOrder, 201);
    }
}
