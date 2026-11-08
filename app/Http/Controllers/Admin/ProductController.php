<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('category');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->q.'%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('availability')) {
            $query->where('is_available', $request->availability === 'available');
        }

        $products = $query->orderBy('sort_order')->orderBy('name')->paginate(12)->withQueryString();

        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        $totalProducts = Product::count();
        $availableProducts = Product::where('is_available', true)->count();
        $unavailableProducts = Product::where('is_available', false)->count();
        $totalCategories = Category::count();

        return view('admin.products.index', compact(
            'products',
            'categories',
            'totalProducts',
            'availableProducts',
            'unavailableProducts',
            'totalCategories',
        ));
    }

    public function create(): View
    {
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        unset($data['image']);

        $data['is_available'] = $request->boolean('is_available');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk '.$product->name.' berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validatedData($request);
        unset($data['image']);

        $data['is_available'] = $request->boolean('is_available');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk '.$product->name.' berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk '.$name.' berhasil dihapus.');
    }

    public function toggleAvailability(Product $product): RedirectResponse
    {
        $product->update(['is_available' => ! $product->is_available]);

        return back()->with(
            'success',
            'Status ketersediaan '.$product->name.' berhasil diperbarui.',
        );
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
    }
}
