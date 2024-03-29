<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Subscriber;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use App\Mail\PriceDecreaseNotification;

class SendSubscribersNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        // Call the method to send price decrease notifications
        $this->sendPriceDecreaseNotifications();
    }
    
    private function sendPriceDecreaseNotifications()
    {
        // Get products with price decreases
        $productsWithPriceDecrease = Product::where('price', '<', 'previous_price')->get();

        foreach ($productsWithPriceDecrease as $product) {
            // Get subscribers for the product
            $subscribers = Subscriber::where('product_id', $product->id)->get();

            foreach ($subscribers as $subscriber) {
                // Send notification email
                Mail::to($subscriber->email)->send(new PriceDecreaseNotification($product));
            }
        }
    }
}