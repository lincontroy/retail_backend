<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShopProduct;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use DB;

class ShopProductController extends Controller
{
    /**
     * Get or create shop product details
     */
    public function getShopProduct($shopId, $productId)
    {
        try {
            // Verify shop exists
            $shop = Shop::find($shopId);
            if (!$shop) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shop not found'
                ], 404);
            }

            // Verify product exists
            $product = Product::find($productId);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            // Find or create shop product
            $shopProduct = ShopProduct::firstOrCreate(
                [
                    'shop_id' => $shopId,
                    'product_id' => $productId
                ],
                [
                    'quantity' => 0,
                    'notes' => null,
                    'images' => []
                ]
            );

            return response()->json([
                'success' => true,
                'shop_product' => [
                    'id' => $shopProduct->id,
                    'shop_id' => $shopProduct->shop_id,
                    'product_id' => $shopProduct->product_id,
                    'quantity' => $shopProduct->quantity,
                    'notes' => $shopProduct->notes,
                    'images' => $shopProduct->image_urls,
                    'created_at' => $shopProduct->created_at,
                    'updated_at' => $shopProduct->updated_at
                ],
                'product' => $product
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch shop product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update shop product with images - FIXED VERSION
     */
  /**
 * Update shop product with images - COMPLETE FIXED VERSION
 */
public function updateShopProduct(Request $request, $shopId, $productId)
{
    DB::beginTransaction();

    try {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:1000',
            'images' => 'nullable|array',
            'images.*' => 'nullable|string', // Base64 images
            'remove_images' => 'nullable|array', // Image paths to remove
        ]);

        if ($validator->fails()) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify shop exists
        $shop = Shop::find($shopId);
        if (!$shop) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Shop not found'
            ], 404);
        }

        // Verify product exists
        $product = Product::find($productId);
        if (!$product) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Find or create shop product
        $shopProduct = ShopProduct::firstOrNew([
            'shop_id' => $shopId,
            'product_id' => $productId
        ]);

        // Get current images
        $currentImages = $shopProduct->images ?? [];

        // Handle image removal
        if ($request->has('remove_images') && is_array($request->remove_images)) {
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
            // Reindex array after removal
            $currentImages = array_values($currentImages);
        }

        // Handle new image uploads
        $newImages = [];
        if ($request->has('images') && is_array($request->images) && !empty($request->images)) {
            foreach ($request->images as $base64Image) {
                if ($base64Image && $this->isBase64Image($base64Image)) {
                    try {
                        $imagePath = $this->saveBase64Image($base64Image, 'shop_products');
                        $newImages[] = $imagePath;
                    } catch (\Exception $e) {
                        // Log error but continue processing other images
                        \Log::error('Failed to save image: ' . $e->getMessage());
                    }
                }
            }
        }

        // Merge existing and new images
        $allImages = array_merge($currentImages, $newImages);

        // Update or create the shop product
        $shopProduct->quantity = $request->quantity;
        $shopProduct->notes = $request->notes;
        $shopProduct->images = $allImages;
        $shopProduct->save();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Shop product updated successfully',
            'shop_product' => [
                'id' => $shopProduct->id,
                'shop_id' => $shopProduct->shop_id,
                'product_id' => $shopProduct->product_id,
                'quantity' => $shopProduct->quantity,
                'notes' => $shopProduct->notes,
                'images' => $shopProduct->image_urls, // This uses the accessor
                'image_paths' => $shopProduct->images // Raw image paths
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Failed to update shop product',
            'error' => $e->getMessage()
        ], 500);
    }
}
    /**
     * Get all products for a specific shop
     */
    public function getShopProducts($shopId)
    {
        try {
            $shopProducts = ShopProduct::where('shop_id', $shopId)
                ->with('product')
                ->get();

            return response()->json([
                'success' => true,
                'shop_products' => $shopProducts->map(function ($shopProduct) {
                    return [
                        'id' => $shopProduct->id,
                        'quantity' => $shopProduct->quantity,
                        'notes' => $shopProduct->notes,
                        'images' => $shopProduct->image_urls,
                        'product' => $shopProduct->product
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch shop products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Helper methods from your existing controller
    private function saveBase64Image($base64Image, $folder = 'images')
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $image = substr($base64Image, strpos($base64Image, ',') + 1);
            $type = strtolower($type[1]);

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

    private function isBase64Image($string)
    {
        return preg_match('/^data:image\/(\w+);base64,/', $string);
    }
}