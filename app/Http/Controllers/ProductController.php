<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    // Валидационные правила
    private function validationRules($id = null)
    {
        return [
            'title' => 'required|string|max:255',
            'category' => 'required|in:fruit,vegetable,flower',
            'image' => $id ? 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_text' => 'required|string|max:500',
            'full_text' => 'required|string'
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->paginate(8);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Валидация данных
        $validated = $request->validate($this->validationRules());
        
        // Обработка изображения
        if ($request->hasFile('image')) {
            $imageName = $this->uploadImage($request->file('image'));
            $validated['image'] = $imageName;
        }
        
        // Создание продукта
        Product::create($validated);
        
        return redirect()->route('products.index')
            ->with('success', 'Продукт успешно создан!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Валидация данных
        $validated = $request->validate($this->validationRules($product->id));
        
        // Обработка изображения
        if ($request->hasFile('image')) {
            // Удаляем старое изображение
            if ($product->image) {
                Storage::delete('public/images/' . $product->image);
            }
            
            $imageName = $this->uploadImage($request->file('image'));
            $validated['image'] = $imageName;
        }
        
        // Обновление продукта
        $product->update($validated);
        
        return redirect()->route('products.index')
            ->with('success', 'Продукт успешно обновлен!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Soft Delete (расширенный уровень)
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('success', 'Продукт успешно удален!');
    }
    
    /**
     * Восстановление удаленного продукта
     */
    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
        
        return redirect()->route('products.index')
            ->with('success', 'Продукт успешно восстановлен!');
    }
    
    /**
     * Обработка загрузки изображения
     */
    private function uploadImage($image)
    {
        $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        
        // Сохраняем оригинал
        $image->storeAs('public/images', $fileName);
        
        // Создаем миниатюру (400x300)
        $thumbnail = Image::make($image->getRealPath());
        $thumbnail->resize(400, 300, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        // Сохраняем миниатюру
        $thumbnail->save(storage_path('app/public/images/thumbnails/' . $fileName));
        
        return $fileName;
    }
}