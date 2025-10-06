<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopProduct;
use App\Models\Shop;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shopProducts = ShopProduct::with(['shop', 'product'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.shop-products.index', compact('shopProducts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shops = Shop::orderBy('name')->get();
        $products = Product::orderBy('english_description')->get();

        return view('admin.shop-products.create', compact('shops', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:1000',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('shop_products', 'public');
                $imagePaths[] = $path;
            }
        }

        $shopProduct = ShopProduct::create([
            'shop_id' => $validated['shop_id'],
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'notes' => $validated['notes'],
            'images' => $imagePaths,
        ]);

        return redirect()->route('admin.shop-products.index')
            ->with('success', 'Shop product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShopProduct $shopProduct)
    {
        $shopProduct->load(['shop', 'product']);
        return view('admin.shop-products.show', compact('shopProduct'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShopProduct $shopProduct)
    {
        $shops = Shop::orderBy('name')->get();
        $products = Product::orderBy('english_description')->get();
        $shopProduct->load(['shop', 'product']);

        return view('admin.shop-products.edit', compact('shopProduct', 'shops', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShopProduct $shopProduct)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:1000',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'string',
        ]);

        // Handle image removal
        $currentImages = $shopProduct->images ?? [];
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageToRemove) {
                // Remove from storage
                if (Storage::disk('public')->exists($imageToRemove)) {
                    Storage::disk('public')->delete($imageToRemove);
                }
                // Remove from array
                $currentImages = array_filter($currentImages, function ($image) use ($imageToRemove) {
                    return $image !== $imageToRemove;
                });
            }
        }

        // Handle new image uploads
        $newImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('shop_products', 'public');
                $newImages[] = $path;
            }
        }

        // Merge existing and new images
        $allImages = array_merge($currentImages, $newImages);

        $shopProduct->update([
            'shop_id' => $validated['shop_id'],
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'notes' => $validated['notes'],
            'images' => $allImages,
        ]);

        return redirect()->route('admin.shop-products.index')
            ->with('success', 'Shop product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShopProduct $shopProduct)
    {
        // Delete associated images
        if ($shopProduct->images) {
            foreach ($shopProduct->images as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        $shopProduct->delete();

        return redirect()->route('admin.shop-products.index')
            ->with('success', 'Shop product deleted successfully.');
    }

    /**
     * Show shop products by shop
     */
    public function byShop(Shop $shop)
    {
        $shopProducts = ShopProduct::where('shop_id', $shop->id)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.shop-products.by-shop', compact('shopProducts', 'shop'));
    }

    /**
     * Show shop products by product
     */
    public function byProduct(Product $product)
    {
        $shopProducts = ShopProduct::where('product_id', $product->id)
            ->with('shop')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.shop-products.by-product', compact('shopProducts', 'product'));
    }
}