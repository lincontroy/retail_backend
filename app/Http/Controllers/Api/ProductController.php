<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Checkin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Get all active products
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 50);
            $page = $request->get('page', 1);
    
            $products = Product::orderBy('english_description')
                ->paginate($perPage, ['*'], 'page', $page);
    
            return response()->json([
                'success' => true,
                'products' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ]
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search products by various fields
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'required|string|min:2',
            'limit' => 'sometimes|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $searchTerm = $request->search;
            $limit = $request->get('limit', 50);

            $products = Product::search($searchTerm)
                ->active()
                ->orderBy('english_description')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'products' => $products,
                'total_found' => $products->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get products for a specific check-in with current quantities
     */
    public function getCheckinProducts($CheckinId)
    {
        try {
            $user = auth()->user();

            // Verify the check-in belongs to the user
            $Checkin = Checkin::where('id', $CheckinId)
                ->where('user_id', $user->id)
                ->firstOrFail();

            // Get all active products
            $allProducts = Product::active()
                ->orderBy('english_description')
                ->get();

            // Get products already associated with this check-in
            $selectedProducts = $Checkin->productsWithPivot()->get();

            // Create a map of selected products for easy lookup
            $selectedProductsMap = [];
            foreach ($selectedProducts as $product) {
                $selectedProductsMap[$product->id] = [
                    'quantity' => $product->pivot->quantity,
                    'notes' => $product->pivot->notes,
                    'images' => $product->pivot->images ? json_decode($product->pivot->images, true) : []
                ];
            }

            // Format response with check-in data
            $formattedProducts = $allProducts->map(function ($product) use ($selectedProductsMap) {
                $selectedData = $selectedProductsMap[$product->id] ?? null;

                return [
                    'id' => $product->id,
                    'barcode' => $product->barcode,
                    'supplier_reference' => $product->supplier_reference,
                    'english_description' => $product->english_description,
                    'brand' => $product->brand,
                    'image' => $product->image_url,
                    'increment' => $product->increment,
                    'pcb' => $product->pcb,
                    'quantity' => $selectedData['quantity'] ?? 0,
                    'notes' => $selectedData['notes'] ?? null,
                    'images' => $selectedData['images'] ?? []
                ];
            });

            return response()->json([
                'success' => true,
                'Checkin' => [
                    'id' => $Checkin->id,
                    'shop_id' => $Checkin->shop_id,
                    'shop_name' => $Checkin->shop->name ?? 'Unknown Shop',
                    'check_in_time' => $Checkin->check_in_time,
                    'latitude' => $Checkin->latitude,
                    'longitude' => $Checkin->longitude
                ],
                'products' => $formattedProducts,
                'selected_count' => $selectedProducts->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch check-in products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update product quantities and images for a check-in
     */
    public function updateCheckinProducts(Request $request, $CheckinId)
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();

            // Verify the check-in belongs to the user
            $Checkin = Checkin::where('id', $CheckinId)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $validator = Validator::make($request->all(), [
                'products' => 'required|array',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:0',
                'products.*.notes' => 'nullable|string|max:500',
                'products.*.images' => 'nullable|array',
                'products.*.images.*' => 'nullable|string', // Base64 encoded images or URLs
                'Checkin_image' => 'nullable|string', // Base64 encoded image
                'Checkin_notes' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle check-in image upload (base64)
            if ($request->has('Checkin_image') && $request->Checkin_image) {
                $imagePath = $this->saveBase64Image($request->Checkin_image, 'Checkins');
                $Checkin->update(['image' => $imagePath]);
            }

            // Update check-in notes
            if ($request->has('Checkin_notes')) {
                $Checkin->update(['Checkin_notes' => $request->Checkin_notes]);
            }

            // Process products
            $productsData = [];
            foreach ($request->products as $productData) {
                $productImages = [];

                // Handle product images (base64)
                if (isset($productData['images']) && is_array($productData['images'])) {
                    foreach ($productData['images'] as $image) {
                        if ($image && $this->isBase64Image($image)) {
                            $imagePath = $this->saveBase64Image($image, 'products');
                            $productImages[] = $imagePath;
                        }
                    }
                }

                $productsData[$productData['product_id']] = [
                    'quantity' => $productData['quantity'],
                    'notes' => $productData['notes'] ?? null,
                    'images' => !empty($productImages) ? json_encode($productImages) : null,
                ];
            }

            // Sync products with the check-in
            $Checkin->productsWithPivot()->sync($productsData);

            DB::commit();

            // Return updated check-in with products
            $updatedCheckin = Checkin::with(['productsWithPivot' => function($query) {
                $query->select('products.*', 'Checkin_product.quantity', 'Checkin_product.notes', 'Checkin_product.images');
            }])->find($CheckinId);

            return response()->json([
                'success' => true,
                'message' => 'Products updated successfully',
                'Checkin' => $updatedCheckin,
                'updated_products_count' => count($productsData)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product details by ID
     */
    public function show($id)
    {
        try {
            $product = Product::active()->findOrFail($id);

            return response()->json([
                'success' => true,
                'product' => $product
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Save base64 image to storage
     */
    private function saveBase64Image($base64Image, $folder = 'images')
    {
        // Check if base64 string contains data URI scheme
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $image = substr($base64Image, strpos($base64Image, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            // Check if file type is allowed
            if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                throw new \Exception('Invalid image type');
            }

            $image = base64_decode($image);
            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('Invalid base64 image format');
        }

        $filename = $folder . '/' . uniqid() . '.' . $type;
        Storage::disk('public')->put($filename, $image);

        return $filename;
    }

    /**
     * Check if string is base64 image
     */
    private function isBase64Image($string)
    {
        return preg_match('/^data:image\/(\w+);base64,/', $string);
    }

    /**
     * Get product statistics
     */
    public function statistics()
    {
        try {
            $user = auth()->user();

            $totalProducts = Product::active()->count();
            $totalCheckins = Checkin::where('user_id', $user->id)->count();
            $productsWithCheckins = DB::table('Checkin_product')
                ->join('check_ins', 'Checkin_product.Checkin_id', '=', 'check_ins.id')
                ->where('check_ins.user_id', $user->id)
                ->distinct('Checkin_product.product_id')
                ->count('Checkin_product.product_id');

            return response()->json([
                'success' => true,
                'statistics' => [
                    'total_products' => $totalProducts,
                    'total_Checkins' => $totalCheckins,
                    'products_with_Checkins' => $productsWithCheckins,
                    'unique_products_used' => $productsWithCheckins
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}