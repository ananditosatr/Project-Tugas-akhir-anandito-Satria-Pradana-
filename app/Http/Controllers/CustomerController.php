<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $categories = Category::active()
            ->orderBy('display_order')
            ->get();

        $menus = Menu::with('category')
            ->available()
            ->get();

        return view('customer.order', compact('categories', 'menus'));
    }

    public function getMenusByCategory(Request $request)
    {
        $categoryId = $request->input('category_id');

        $query = Menu::with('category')->available();

        if ($categoryId && $categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }

        $menus = $query->get();

        return response()->json($menus);
    }
}
