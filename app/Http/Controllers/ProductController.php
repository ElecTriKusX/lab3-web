<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

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
        $products = Product::with(['user', 'comments'])->latest()->paginate(8);
        return view('products.index', compact('products'));
    }

    // Список продуктов конкретного пользователя
    public function userProducts($name)
    {
        $user = User::where('name', $name)
            ->with(['followers', 'following'])
            ->firstOrFail();
        
        $products = $user->products()
            ->with('comments')
            ->latest()
            ->paginate(8);
        
        return view('products.user-products', compact('products', 'user'));
    }

    // Список всех пользователей
    public function users()
    {
        $users = User::withCount('products')
            ->with(['followers', 'following'])
            ->paginate(10);
        
        return view('products.users', compact('users'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());
        
        // Добавляем user_id текущего пользователя
        $validated['user_id'] = auth()->id();
        
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
        // Загружаем продукт с пользователем и комментариями (включая авторов комментариев)
        $product->load(['user', 'comments.user']);
        
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        // Проверка прав через Gate
        if (Gate::denies('update-product', $product)) {
            abort(403, 'У вас нет прав на редактирование этого продукта');
        }
        
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // Проверка прав через Gate
        if (Gate::denies('update-product', $product)) {
            abort(403, 'У вас нет прав на редактирование этого продукта');
        }
        
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
        // Проверка прав через Gate
        if (Gate::denies('delete-product', $product)) {
            abort(403, 'У вас нет прав на удаление этого продукта');
        }
        
        // Мягкое удаление
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('success', 'Продукт перемещен в корзину!');
    }

    /**
     * Показать корзину (удаленные продукты) - только для админа
     */
    public function trashed()
    {
        if (Gate::denies('view-trash')) {
            abort(403, 'Доступ только для администраторов');
        }
        
        $products = Product::onlyTrashed()->with('user')->paginate(8);
        return view('products.trashed', compact('products'));
    }

    /**
     * Восстановить удаленный продукт - только для админа
     */
    public function restore($id)
    {
        if (Gate::denies('restore-product')) {
            abort(403, 'Доступ только для администраторов');
        }
        
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
        
        return redirect()->route('products.trashed')
            ->with('success', 'Продукт "' . $product->title . '" успешно восстановлен!');
    }

    /**
     * Полное удаление из базы данных - только для админа
     */
    public function forceDelete($id)
    {
        if (Gate::denies('force-delete-product')) {
            abort(403, 'Доступ только для администраторов');
        }
        
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

    /**
     * Очистить всю корзину - только для админа
     */
    public function forceDeleteAll()
    {
        if (Gate::denies('force-delete-product')) {
            abort(403, 'Доступ только для администраторов');
        }
        
        $products = Product::onlyTrashed()->get();
        
        foreach ($products as $product) {
            if ($product->image) {
                Storage::delete('public/images/' . $product->image);
            }
            $product->forceDelete();
        }
        
        return redirect()->route('products.trashed')
            ->with('success', 'Корзина полностью очищена!');
    }
    
    private function uploadImage($image)
    {
        $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('storage/images'), $fileName);
        return $fileName;
    }
}