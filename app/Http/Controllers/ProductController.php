<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\DetailProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Lấy giá trị tìm kiếm và phân trang từ query parameters
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10); // Mặc định là 10 sản phẩm mỗi trang

        // Xây dựng query để tìm kiếm và phân trang
        $query = Product::with('category');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('detailed_description', 'like', "%{$search}%");
        }

        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    public function detail($id){
        $detailProducts = DetailProduct::with('size')->where('product_id',$id)->get();
        return response()->json($detailProducts);
    }

    public function storeDetail(Request $request, $id){
        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'size_id' => 'required|exists:sizes,id',
            'price' => 'required|numeric'
        ]);

        // Tạo chi tiết sản phẩm mới
        $detailProduct = new DetailProduct();
        $detailProduct->product_id = $id;
        $detailProduct->size_id = $validatedData['size_id'];
        $detailProduct->price = $validatedData['price'];
        $detailProduct->save();

        return response()->json($detailProduct, 201);
    }

    public function deleteDetail(Request $request, $id)
    {
        // Tìm chi tiết sản phẩm theo ID
        $detailProduct = DetailProduct::find($id);

        if (!$detailProduct) {
            return response()->json(['error' => 'Detail product not found'], 404);
        }

        // Xóa chi tiết sản phẩm
        $detailProduct->delete();

        return response()->json(['message' => 'Detail product deleted successfully'], 200);
    }

    // Tạo sản phẩm mới
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'detailed_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'slug' => 'required|string|unique:products,slug',
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id'
        ]);

        // Xử lý upload ảnh
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $validatedData['image'] = $imagePath;
        }

        // Tạo mới sản phẩm
        $product = Product::create($validatedData);

        return response()->json($product, 201);
    }

    // Lấy thông tin một sản phẩm cụ thể
    public function show($id)
    {
        $product = Product::with('category')->find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    // Cập nhật thông tin sản phẩm
    public function update(Request $request, $id)
    {
        // Tìm sản phẩm theo ID
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Xác thực dữ liệu
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'detailed_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'slug' => 'required|string|unique:products,slug,' . $id,
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id'
        ]);

        // Xử lý file image nếu có
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $validatedData['image'] = $imagePath;
        }

        // Cập nhật sản phẩm
        $product->update($validatedData);

        return response()->json($product);
    }

    // Xóa sản phẩm
    public function destroy(Product $product)
    {
        // Xóa ảnh liên quan nếu có
        if ($product->image && Storage::exists('public/' . $product->image)) {
            Storage::delete('public/' . $product->image);
        }
        // Xóa sản phẩm
        $product->delete();
        return response()->json(['message' => 'success'], 200);
    }

}
