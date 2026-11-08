<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $totalCategories = $categories->count();
        $activeCategories = $categories->where('is_active', true)->count();
        $totalProducts = $categories->sum('products_count');

        return view('admin.categories.index', compact(
            'categories',
            'totalCategories',
            'activeCategories',
            'totalProducts',
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $category = Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori '.$category->name.' berhasil ditambahkan.');
    }

    public function edit(Category $category): View
    {
        $categories = Category::query()
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $totalCategories = $categories->count();
        $activeCategories = $categories->where('is_active', true)->count();
        $totalProducts = $categories->sum('products_count');

        return view('admin.categories.index', compact(
            'category',
            'categories',
            'totalCategories',
            'activeCategories',
            'totalProducts',
        ));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $this->validatedData($request, $category);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori '.$category->name.' berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->with(
                'error',
                'Kategori '.$category->name.' masih memiliki produk. Pindahkan atau hapus produknya terlebih dahulu.',
            );
        }

        $name = $category->name;
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori '.$name.' berhasil dihapus.');
    }

    public function toggleActive(Category $category): RedirectResponse
    {
        $category->update(['is_active' => ! $category->is_active]);

        return back()->with(
            'success',
            'Status kategori '.$category->name.' berhasil diperbarui.',
        );
    }

    private function validatedData(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:categories,name'.($category ? ','.$category->id : ''),
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
    }
}
