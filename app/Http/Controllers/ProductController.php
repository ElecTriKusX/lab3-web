<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private function validationRules($id = null)
    {
        return [
            'title' => 'required|string|max:255',
            'category' => 'required|in:fruit,vegetable,flower',
            'image' => $id ? 'sometimes|image|mimes:jpeg,png,jpg,gif|max:1024|dimensions:max_width=150,max_height=150' : 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024|dimensions:max_width=150,max_height=150',
            'short_text' => 'required|string|max:500',
            'full_text' => 'required|string'
        ];
    }

    public function index()
    {
        $products = Product::latest()->paginate(8);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());
        
        if ($request->hasFile('image')) {
            $imageName = $this->uploadImage($request->file('image'));
            $validated['image'] = $imageName;
        }
        
        Product::create($validated);
        
        return redirect()->route('products.index')
            ->with('success', 'Продукт успешно создан!');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate($this->validationRules($product->id));
        
        if ($request->hasFile('image')) {
            // Удаляем старое изображение
            if ($product->image) {
                Storage::delete('storage/images/' . $product->image);
            }
            
            $imageName = $this->uploadImage($request->file('image'));
            $validated['image'] = $imageName;
        }
        
        $product->update($validated);
        
        return redirect()->route('products.index')
            ->with('success', 'Продукт успешно обновлен!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::delete('storage/images/' . $product->image);
        }
        
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('success', 'Продукт успешно удален!');
    }
    
    private function uploadImage($image)
    {
        $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        
        $image->move(public_path('storage/images'), $fileName);
        
        return $fileName;
    }
}