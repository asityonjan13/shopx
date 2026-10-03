<?php

use App\Models\Address;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('returns the storefront catalog', function () {
    $this->getJson('/api/home')->assertOk()->assertJsonStructure(['data' => ['settings', 'categories', 'featured']]);
});

it('checks out a product from the cart', function () {
    $category = Category::create([
        'name' => 'Audio',
        'slug' => 'audio',
        'position' => 0,
        'is_active' => true,
    ]);

    $vendor = User::factory()->vendor()->create();
    $store = Store::create([
        'seller_id' => $vendor->id,
        'name' => 'Northwind',
        'slug' => 'northwind',
        'email' => 'store@example.com',
        'phone' => '123',
        'address' => 'Market Street',
    ]);

    $product = Product::create([
        'store_id' => $store->id,
        'category_id' => $category->id,
        'name' => 'Desk Lamp',
        'slug' => 'desk-lamp',
        'price' => 40,
        'stock' => 5,
        'is_active' => true,
    ]);

    $customer = User::factory()->create();
    $address = Address::create([
        'user_id' => $customer->id,
        'full_name' => 'Test User',
        'phone' => '555',
        'line1' => '12 Durbar Marg',
        'city' => 'Kathmandu',
        'postal_code' => '44600',
        'country' => 'Nepal',
        'is_default' => true,
    ]);

    Sanctum::actingAs($customer);

    $this->postJson('/api/cart', [
        'product_id' => $product->id,
        'quantity' => 2,
    ])->assertOk()->assertJsonPath('data.count', 2);

    $this->postJson('/api/orders', [
        'address_id' => $address->id,
    ])->assertCreated()->assertJsonPath('data.total', 80);

    expect($product->fresh()->stock)->toBe(3);
});
