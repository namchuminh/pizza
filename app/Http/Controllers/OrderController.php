<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Lấy danh sách tất cả các orders với tìm kiếm và phân trang
    public function index(Request $request)
    {
        if(auth()->user()->role_id == 3){
            // Lấy giá trị tìm kiếm và phân trang từ query parameters
            $search = $request->query('search');
            $perPage = $request->query('per_page', 10); // Mặc định là 10 orders mỗi trang

            // Xây dựng query để tìm kiếm và phân trang
            $query = Order::query()
                        ->where('user_id', auth()->user()->id); // Thêm điều kiện user_id

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('order_code', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            $orders = $query->paginate($perPage);

            return response()->json($orders);
        }else{
            $search = $request->query('search');
            $perPage = $request->query('per_page', 10); // Mặc định là 10 orders mỗi trang

            // Xây dựng query để tìm kiếm và phân trang
            $query = Order::with('customer'); // Thêm with('user') để tải thông tin của User liên quan

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('order_code', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            $orders = $query->paginate($perPage);

            return response()->json($orders);
        }
    }

    // Tạo order mới
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'total_amount' => 'required|numeric',
            'coupon_id' => 'nullable|exists:coupons,id'
        ]);

        $validatedData['customer_id'] = auth()->user()->id;
        $validatedData['payment'] = 0;
        $validatedData['status'] = 1;
        
        // Tạo mới order
        $order = Order::create($validatedData);

        return response()->json($order, 201);
    }

    // Lấy thông tin một order cụ thể
    public function show($id)
    {
        $order = Order::with('customer')->with('coupon')->find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if((auth()->user()->role_id == 3) && ($order->customer_id != auth()->user()->id)){
            return response()->json(['error' => 'Unauthorized'], 401);
        }


        return response()->json($order);
    }

    // Hủy đơn hàng (cập nhật status = 0)
    public function cancel($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->update(['status' => 0]);

        return response()->json(['message' => 'Order cancelled successfully']);
    }

    public function status($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if($order->id == 0){
            return response()->json(['error' => 'Unauthorized']);
        }

        $status = $order->status + 1;

        if($status >= 4){
            return response()->json(['error' => 'Unauthorized']);
        }

        $order->status = $status;

        $order->save();

        return response()->json(['status' => $status]);
    }

    // Xác nhận thanh toán đơn hàng (cập nhật payment = 1)
    public function pay($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->update(['payment' => 1]);

        return response()->json(['message' => 'Order paid successfully']);
    }
}
