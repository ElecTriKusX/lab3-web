<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TokenController extends Controller
{
    /**
     * Показать страницу с токенами пользователя
     */
    public function index()
    {
        $user = auth()->user();
        $tokens = $user->tokens;

        return view('tokens.index', compact('tokens'));
    }

    /**
     * Создать новый токен для пользователя
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Создаем токен с неограниченным сроком действия
        $token = auth()->user()->createToken($request->name);

        return redirect()->route('tokens.index')
            ->with('success', 'Токен успешно создан!')
            ->with('token', $token->plainTextToken);
    }

    /**
     * Удалить токен
     */
    public function destroy($tokenId)
    {
        $token = auth()->user()->tokens()->where('id', $tokenId)->first();

        if (!$token) {
            return redirect()->route('tokens.index')
                ->with('error', 'Токен не найден');
        }

        $token->delete();

        return redirect()->route('tokens.index')
            ->with('success', 'Токен успешно удален');
    }
}