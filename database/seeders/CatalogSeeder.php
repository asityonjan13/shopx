<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Category;
use App\Models\Kyc;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'site_name' => 'ShopX',
            'site_email' => 'hello@shopx.test',
            'site_phone' => '+1 415 555 0148',
        ] as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $electronics = $this->category('Electronics', null, 0);
        $audio = $this->category('Audio', $electronics->id, 0);
        $phones = $this->category('Phones', $electronics->id, 1);
        $home = $this->category('Home', null, 1);
        $kitchen = $this->category('Kitchen', $home->id, 0);
        $fashion = $this->category('Fashion', null, 2);
        $apparel = $this->category('Apparel', $fashion->id, 0);

        $vendor = User::where('email', 'vendor@gmail.com')->first();
        if (! $vendor) {
            return;
        }

        Kyc::updateOrCreate(
            ['user_id' => $vendor->id],
            [
                'status' => 'approved',
                'verified_at' => now(),
                'full_name' => 'Vendor User',
                'date_of_birth' => '1992-04-18',
                'gender' => 'female',
                'full_address' => '18 Market Street, Kathmandu',
                'document_type' => 'id_card',
                'document_scan_copy' => 'documents/seed-id.txt',
            ]
        );

        $store = Store::updateOrCreate(
            ['seller_id' => $vendor->id],
            [
                'name' => 'Northwind Goods',
                'slug' => 'northwind-goods',
                'email' => 'northwind@shopx.test',
                'phone' => '+977 1 555 0199',
                'address' => '18 Market Street, Kathmandu',
                'short_description' => 'Everyday objects, made to be used hard.',
                'long_description' => 'Northwind Goods stocks audio, home, and clothing pieces chosen for materials and repairability. Orders ship from Kathmandu.',
            ]
        );

        $products = [
            ['Cedar Wireless Headphones', $audio->id, 129, 149, 18, true, true, 99, 'Closed-back headphones with a 30-hour battery and a replaceable cable.'],
            ['Harbor Phone Case', $phones->id, 24, null, 40, false, false, null, 'Matte recycled case with a raised lip around the camera.'],
            ['Lumen Desk Lamp', $home->id, 68, null, 14, true, false, null, 'A dimmable aluminum lamp with a warm 2700K bulb included.'],
            ['Stoneware Pour-Over Set', $kitchen->id, 42, 48, 22, false, true, 34, 'Two-cup dripper and carafe, glazed in ash grey.'],
            ['Merino Crew Sweater', $apparel->id, 88, null, 16, true, false, null, 'Mid-weight merino that layers under a jacket without itching.'],
            ['Canvas Market Tote', $apparel->id, 36, null, 30, false, false, null, 'Waxed canvas tote with an interior pocket and leather handles.'],
            ['Compact Bluetooth Speaker', $audio->id, 79, null, 20, false, false, null, 'A pocket speaker loud enough for a kitchen, with a 12-hour charge.'],
            ['Ceramic Table Planter', $home->id, 28, null, 25, false, false, null, 'Unglazed planter sized for herbs, with a matching saucer.'],
            ['Brass Kitchen Scale', $kitchen->id, 54, 62, 12, true, false, null, 'A 5kg scale with a removable stainless platform.'],
            ['Everyday Chino Trousers', $apparel->id, 72, null, 18, false, false, null, 'Straight-leg cotton twill with a plain hem.'],
            ['Noise-isolating Earbuds', $audio->id, 59, 69, 28, false, true, 45, 'Wired earbuds with silicone tips in three sizes.'],
            ['Folding Phone Stand', $phones->id, 18, null, 35, false, false, null, 'Brushed steel stand that folds flat into a bag.'],
        ];

        foreach ($products as $index => [$name, $categoryId, $price, $compare, $stock, $featured, $flash, $flashPrice, $summary]) {
            $slug = str($name)->slug()->toString();
            Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'store_id' => $store->id,
                    'category_id' => $categoryId,
                    'name' => $name,
                    'sku' => 'NW-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                    'price' => $price,
                    'compare_price' => $compare,
                    'stock' => $stock,
                    'short_description' => $summary,
                    'description' => $summary.' Sold by Northwind Goods and packed in recycled mailers.',
                    'is_active' => true,
                    'is_featured' => $featured,
                    'is_flash_sale' => $flash,
                    'flash_price' => $flashPrice,
                    'created_at' => now()->subDays(count($products) - $index),
                    'updated_at' => now()->subDays(count($products) - $index),
                ]
            );
        }

        $customer = User::where('email', 'user@gmail.com')->first();
        if ($customer && ! $customer->addresses()->exists()) {
            Address::create([
                'user_id' => $customer->id,
                'label' => 'Home',
                'full_name' => 'Test User',
                'phone' => '+977 980 000 0000',
                'line1' => '12 Durbar Marg',
                'city' => 'Kathmandu',
                'state' => 'Bagmati',
                'postal_code' => '44600',
                'country' => 'Nepal',
                'is_default' => true,
            ]);
        }
    }

    private function category(string $name, ?int $parentId, int $position): Category
    {
        return Category::updateOrCreate(
            ['slug' => str($name)->slug()->toString()],
            [
                'name' => $name,
                'parent_id' => $parentId,
                'position' => $position,
                'is_active' => true,
            ]
        );
    }
}
