<?php

namespace App\Http\Controllers;

use App\Models\Soles;
use Illuminate\Http\Request;

class SolesController extends Controller
{
    // Lấy danh sách tất cả các soles với tìm kiếm và phân trang
    public function index(Request $request)
    {
        // Lấy giá trị tìm kiếm và phân trang từ query parameters
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10); // Mặc định là 10 soles mỗi trang

        // Xây dựng query để tìm kiếm và phân trang
        $query = Soles::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $soles = $query->paginate($perPage);

        return response()->json($soles);
    }

    // Tạo soles mới
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Tạo mới soles
        $soles = Soles::create($validatedData);

        return response()->json($soles, 201);
    }

    // Lấy thông tin một soles cụ thể
    public function show($id)
    {
        $soles = Soles::find($id);
        if (!$soles) {
            return response()->json(['error' => 'Soles not found'], 404);
        }
        return response()->json($soles);
    }

    // Cập nhật thông tin soles
    public function update(Request $request, $id)
    {
        // Tìm soles theo ID
        $soles = Soles::find($id);
        if (!$soles) {
            return response()->json(['error' => 'Soles not found'], 404);
        }

        // Xác thực dữ liệu
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Cập nhật soles
        $soles->update($validatedData);

        return response()->json($soles);
    }

    // Xóa soles
    public function destroy($id)
    {
        $soles = Soles::find($id);
        if (!$soles) {
            return response()->json(['error' => 'Soles not found'], 404);
        }

        // Xóa soles
        $soles->delete();
        return response()->json(['message' => 'Soles deleted successfully']);
    }
}
