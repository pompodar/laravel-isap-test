<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement; 

class ProcessXMLFeeds implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        // URLs for the XML feeds
        $feedPath = public_path('feed_1.xml');

        try {
            // Fetch XML data
            $xmlData = file_get_contents($feedPath);

            // Parse XML data and update database
            $this->parseAndUpdateData($xmlData);

            // Generate subscriber entries for each product
            $this->generateSubscribers();

            Log::info("feed fetched and processed successfully.");
        } catch (\Exception $e) {
            Log::error("Error fetching feed: {$e->getMessage()}");
        }
    }

    private function parseAndUpdateData($xmlData)
    {
        $xml = new SimpleXMLElement($xmlData);

        foreach ($xml->Product as $productData) {
            $productId = (string)$productData->Product_ID;
            $category = (string)$productData->Category;
            $categoryID = (string)$productData->Category_ID;

            // Check if category exists, if not create it
            $categoryModel = Category::firstOrCreate(
                ['category_id' => $categoryID],
                ['name' => $category]
            );

            // Get the existing product or create a new one
            $product = Product::updateOrCreate(
                ['product_id' => $productId],
                [
                    'previous_price' => $product->price, // Store the current price as previous price
                    'sku' => (string)$productData->SKU,
                    'name' => (string)$productData->Name,
                    'price' => (float)$productData->Price,
                    'retail_price' => (float)$productData->Retail_Price,
                    'description' => (string)$productData->Description,
                    'category_id' => $categoryModel->id,
                    'brand' => (string)$productData->Brand,
                    'size' => (string)$productData->Size,
                    'rating_avg' => (float)$productData->Rating_Avg,
                    'rating_count' => (int)$productData->Rating_Count,
                    'inventory_count' => (int)$productData->Inventory_Count,
                ]
            );

            // Check if the product has any manual edits
            $manualEdits = ManualEdit::where('product_id', $product->id)->get();

            // Update only the fields that were not manually edited
            if ($manualEdits->isNotEmpty()) {
                foreach ($manualEdits as $manualEdit) {
                    $fieldName = $manualEdit->field_name;
                    $originalValue = $manualEdit->original_value;

                    // Check if the original value is different from the value in the XML feed
                    if ($product->{$fieldName} == $originalValue) {
                        // If it's the same, update the field with the value from the XML feed
                        $product->{$fieldName} = (string)$productData->{$fieldName};
                    }
                }
            }

            // Save the updated product
            $product->save();
        }
    }

    private function generateSubscribers()
    {
        // Get all products
        $products = Product::all();

        foreach ($products as $product) {
            // Generate three subscriber entries for each product
            for ($i = 0; $i < 3; $i++) {
                // Generate random email address
                $email = 'subscriber' . $i . '@example.com';

                // Create subscriber entry
                Subscriber::firstOrCreate([
                    'email' => $email,
                    'product_id' => $product->id
                ]);
            }
        }
    }

}

