<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('category')->get();
        return response()->json($products);
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $updatedFields = $request->all();
        
        // Loop through updated fields
        foreach ($updatedFields as $fieldName => $updatedValue) {
            // Check if the field was manually edited
            if ($product->{$fieldName} !== $updatedValue) {
                // Store the original value in the manual_edits table
                ManualEdit::create([
                    'product_id' => $product->id,
                    'field_name' => $fieldName,
                    'original_value' => $product->{$fieldName}
                ]);
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'brand' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:50',
        ]);

        $product->update($request->only([
            'name', 'sku', 'price', 'description', 'brand', 'size'
        ]));
        
        return response()->json($product);
    }
}
