@component('mail::message')
# Price Decrease Notification

The price of the product "{{ $product->name }}" has decreased.

New Price: ${{ $product->price }}

Thank you for your subscription.

@endcomponent