<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Lấy danh sách tất cả các Users với tìm kiếm và phân trang
    public function index(Request $request)
    {
        if(auth()->user()->role == 'manager'){
            // Lấy giá trị tìm kiếm và phân trang từ query parameters
            $search = $request->query('search');
            $perPage = $request->query('per_page', 10); // Mặc định là 10 User mỗi trang

            // Xây dựng query để tìm kiếm và phân trang
            $query = User::query();

            if ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            }

            $Users = $query->paginate($perPage);

            return response()->json($Users);
        }else if(auth()->user()->role == "employee"){
            // Lấy giá trị tìm kiếm và phân trang từ query parameters
            $search = $request->query('search');
            $perPage = $request->query('per_page', 10); // Mặc định là 10 customer mỗi trang

            // Xây dựng query để tìm kiếm và phân trang
            $query = User::where('role', 'customer');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
                });
            }

            $customers = $query->paginate($perPage);

            return response()->json($customers);
        }
    }

    // Lấy thông tin một User cụ thể
    public function show($id)
    {
        $User = User::find($id);
        if (!$User) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json($User);
    }

    public function profile()
    {
        $id = auth()->user()->id;
        $User = User::find($id);
        if (!$User) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json($User);
    }

    // Cập nhật thông tin customer (có cả đổi mật khẩu)
    public function update(Request $request)
    {
        // Tìm customer theo ID
        $user = User::find(auth()->user()->id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Xác thực dữ liệu
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->user()->id,
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255'
        ]);

        // Cập nhật mật khẩu nếu có
        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->input('password'));
        }

        // Cập nhật thông tin user
        $user->update($validatedData);

        return response()->json($user);
    }

    // Cấm khách hàng (đổi status bằng 0)
    public function block($id)
    {
        if(auth()->user()->role == 'customer'){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $User = User::find($id);
        if (!$User) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if(($User->role == "manager") && auth()->user()->role == 'employee'){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if($User->id == auth()->user()->id){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $User->status = 0;
        $User->save();

        return response()->json(['message' => 'User blocked successfully']);
    }
}
