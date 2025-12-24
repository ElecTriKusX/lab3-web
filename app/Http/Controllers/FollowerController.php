<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Подписаться на пользователя
     */
    public function follow(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Вы не можете подписаться на себя!');
        }

        $followed = auth()->user()->follow($user->id);

        if ($followed) {
            return redirect()->back()->with('success', "Вы подписались на {$user->name}");
        }

        return redirect()->back()->with('error', 'Вы уже подписаны на этого пользователя');
    }

    /**
     * Отписаться от пользователя
     */
    public function unfollow(User $user)
    {
        $unfollowed = auth()->user()->unfollow($user->id);

        if ($unfollowed) {
            return redirect()->back()->with('success', "Вы отписались от {$user->name}");
        }

        return redirect()->back()->with('error', 'Вы не подписаны на этого пользователя');
    }

    /**
     * Показать подписки пользователя
     */
    public function following(User $user)
    {
        $following = $user->following()->paginate(20);
        return view('followers.following', compact('user', 'following'));
    }

    /**
     * Показать подписчиков пользователя
     */
    public function followers(User $user)
    {
        $followers = $user->followers()->paginate(20);
        return view('followers.followers', compact('user', 'followers'));
    }

    /**
     * Лента новостей (продукты от друзей)
     */
    public function feed()
    {
        $products = auth()->user()->getFeed(12);
        return view('followers.feed', compact('products'));
    }
}