<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Получить список всех продуктов
     * GET /api/products
     */
    public function index()
    {
        $products = Product::with(['user', 'comments'])->latest()->paginate(15);
        
        return ProductResource::collection($products);
    }

    /**
     * Получить конкретный продукт
     * GET /api/products/{id}
     */
    public function show($id)
    {
        $product = Product::with(['user', 'comments.user'])->findOrFail($id);
        
        return new ProductResource($product);
    }

    /**
     * Создать новый продукт
     * POST /api/products
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:255',
            'category' => 'required|in:fruit,vegetable,flower',
            'short_text' => 'required|string|min:10|max:500',
            'full_text' => 'required|string|min:50',
            'image' => 'nullable|image|mimes:jpeg,png,gif|max:1024|dimensions:max_width=150,max_height=150',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['user_id'] = auth('sanctum')->id();

        // Обработка изображения
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/images'), $fileName);
            $data['image'] = $fileName;
        }

        $product = Product::create($data);
        $product->load('user');

        return new ProductResource($product);
    }

    /**
     * Обновить продукт
     * PUT/PATCH /api/products/{id}
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Проверка прав доступа
        if (auth('sanctum')->id() !== $product->user_id && !auth('sanctum')->user()->is_admin) {
            return response()->json([
                'message' => 'У вас нет прав на редактирование этого продукта'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|min:3|max:255',
            'category' => 'sometimes|required|in:fruit,vegetable,flower',
            'short_text' => 'sometimes|required|string|min:10|max:500',
            'full_text' => 'sometimes|required|string|min:50',
            'image' => 'nullable|image|mimes:jpeg,png,gif|max:1024|dimensions:max_width=150,max_height=150',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        // Обработка изображения
        if ($request->hasFile('image')) {
            // Удаляем старое изображение
            if ($product->image && file_exists(public_path('storage/images/' . $product->image))) {
                unlink(public_path('storage/images/' . $product->image));
            }

            $image = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/images'), $fileName);
            $data['image'] = $fileName;
        }

        $product->update($data);
        $product->load('user');

        return new ProductResource($product);
    }

    /**
     * Удалить продукт
     * DELETE /api/products/{id}
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Проверка прав доступа
        if (auth('sanctum')->id() !== $product->user_id && !auth('sanctum')->user()->is_admin) {
            return response()->json([
                'message' => 'У вас нет прав на удаление этого продукта'
            ], 403);
        }

        $product->delete();

        return response()->json([
            'message' => 'Продукт успешно удален'
        ], 200);
    }
}