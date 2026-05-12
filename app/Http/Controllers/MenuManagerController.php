<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuManagerController extends Controller
{
    private function getMimeType($image)
    {
        try {
            return $image->getMimeType();
        } catch (\Symfony\Component\Mime\Exception\LogicException $e) {
            // Fallback: try client MIME type
            $clientMimeType = $image->getClientMimeType();
            if ($clientMimeType && $clientMimeType !== 'application/octet-stream') {
                return $clientMimeType;
            }

            // Fallback: detect by extension
            $extension = strtolower($image->getClientOriginalExtension());
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'bmp' => 'image/bmp',
                'svg' => 'image/svg+xml',
            ];

            return $mimeTypes[$extension] ?? 'application/octet-stream';
        }
    }

    // ===== MENU =====

    public function index()
    {
        $categories = Category::orderBy('display_order')->get();
        $menus = Menu::with('category')->orderBy('category_id')->orderBy('name')->get();

        return view('kasir.menu.index', compact('categories', 'menus'));
    }

    public function createMenu()
    {
        $categories = Category::active()->orderBy('display_order')->get();
        return view('kasir.menu.form', compact('categories'));
    }

    public function storeMenu(Request $request)
    {
        $validated = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:200',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'is_available' => 'boolean',
            'image'        => 'nullable|file|max:2048',
        ], [
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Manual image validation
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = strtolower($image->getClientOriginalExtension());
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
            
            if (!in_array($extension, $allowedExtensions)) {
                return back()->withInput()->withErrors(['image' => 'Format gambar harus JPEG, PNG, JPG, GIF, WEBP, BMP, atau SVG.']);
            }
        }

        $data = [
            'category_id'  => $validated['category_id'],
            'name'         => $validated['name'],
            'description'  => $validated['description'] ?? null,
            'price'        => $validated['price'],
            'stock'        => $validated['stock'],
            'is_available' => $request->boolean('is_available', true),
        ];

        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $imageData = base64_encode(file_get_contents($image->getRealPath()));

                $mimeType = $this->getMimeType($image);

                $data['image_base64'] = 'data:' . $mimeType . ';base64,' . $imageData;
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['image' => 'Gagal memproses gambar: ' . $e->getMessage()]);
            }
        }

        Menu::create($data);

        return redirect()->route('kasir.menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function editMenu(Menu $menu)
    {
        $categories = Category::active()->orderBy('display_order')->get();
        return view('kasir.menu.form', compact('menu', 'categories'));
    }

    public function updateMenu(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:200',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'is_available' => 'boolean',
            'image'        => 'nullable|file|max:2048',
        ], [
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Manual image validation
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = strtolower($image->getClientOriginalExtension());
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
            
            if (!in_array($extension, $allowedExtensions)) {
                return back()->withInput()->withErrors(['image' => 'Format gambar harus JPEG, PNG, JPG, GIF, WEBP, BMP, atau SVG.']);
            }
        }

        $data = [
            'category_id'  => $validated['category_id'],
            'name'         => $validated['name'],
            'description'  => $validated['description'] ?? null,
            'price'        => $validated['price'],
            'stock'        => $validated['stock'],
            'is_available' => $request->boolean('is_available', true),
        ];

        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $imageData = base64_encode(file_get_contents($image->getRealPath()));

                $mimeType = $this->getMimeType($image);

                $data['image_base64'] = 'data:' . $mimeType . ';base64,' . $imageData;
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['image' => 'Gagal memproses gambar: ' . $e->getMessage()]);
            }
        }

        if ($request->boolean('remove_image')) {
            $data['image_base64'] = null;
        }

        $menu->update($data);

        return redirect()->route('kasir.menu.index')->with('success', 'Menu berhasil diupdate.');
    }

    public function destroyMenu(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('kasir.menu.index')->with('success', 'Menu berhasil dihapus.');
    }

    public function toggleAvailable(Menu $menu)
    {
        $menu->update(['is_available' => !$menu->is_available]);
        return response()->json([
            'success' => true,
            'is_available' => $menu->is_available,
        ]);
    }

    // ===== CATEGORY =====

    public function createCategory()
    {
        return view('kasir.category.form');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:100|unique:categories,name',
            'display_order' => 'required|integer|min:0',
            'status'        => 'required|in:active,inactive',
        ]);

        Category::create($validated);

        return redirect()->route('kasir.menu.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function editCategory(Category $category)
    {
        return view('kasir.category.form', compact('category'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:100|unique:categories,name,' . $category->id,
            'display_order' => 'required|integer|min:0',
            'status'        => 'required|in:active,inactive',
        ]);

        $category->update($validated);

        return redirect()->route('kasir.menu.index')->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->menus()->count() > 0) {
            return redirect()->route('kasir.menu.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih memiliki menu.');
        }

        $category->delete();
        return redirect()->route('kasir.menu.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
