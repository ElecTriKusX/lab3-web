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
            'title' => [
                'required',
                'string',
                'min:3',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (trim($value) === '') {
                        $fail('Название не может состоять только из пробелов.');
                    }
                }
            ],
            'category' => 'required|in:fruit,vegetable,flower',
            'image' => [
                $id ? 'sometimes' : 'nullable',
                'image',
                'mimes:jpeg,png,gif',
                'max:1024',
                'dimensions:max_width=150,max_height=150'
            ],
            'short_text' => 'required|string|min:10|max:500',
            'full_text' => 'required|string|min:50',
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

    /**
     * Показать корзину (удаленные продукты)
     */
    public function trashed()
    {
        $products = Product::onlyTrashed()->paginate(8);
        return view('products.trashed', compact('products'));
    }

    /**
     * Восстановить удаленный продукт
     */
    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
        
        return redirect()->route('products.trashed')
            ->with('success', 'Продукт "' . $product->title . '" успешно восстановлен!');
    }

    /**
     * Полное удаление из базы данных
     */
    public function forceDelete($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        
        // Удаляем изображение
        if ($product->image) {
            Storage::delete('public/images/' . $product->image);
        }
        
        $title = $product->title;
        $product->forceDelete();
        
        return redirect()->route('products.trashed')
            ->with('success', 'Продукт "' . $title . '" полностью удален из базы данных!');
    }
    
    private function uploadImage($image)
    {
        $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        
        $image->move(public_path('storage/images'), $fileName);
        
        return $fileName;
    }
}