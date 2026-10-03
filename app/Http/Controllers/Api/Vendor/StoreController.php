<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreUpdateRequest;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use App\Support\Slug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $store = $request->user()->store;

        return response()->json([
            'data' => $store ? new StoreResource($store) : null,
        ]);
    }

    public function update(StoreUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $store = $user->store;

        $data = [
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'phone' => $request->string('phone')->toString(),
            'address' => $request->string('address')->toString(),
            'short_description' => $request->string('short_description')->toString(),
            'long_description' => $request->string('long_description')->toString(),
            'slug' => Slug::unique(Store::class, $request->string('name')->toString(), $store?->id),
        ];

        if ($request->hasFile('logo')) {
            $this->deletePublicFile($store?->logo);
            $data['logo'] = $request->file('logo')->store('stores', 'public');
        }

        if ($request->hasFile('banner')) {
            $this->deletePublicFile($store?->banner);
            $data['banner'] = $request->file('banner')->store('stores', 'public');
        }

        $store = Store::updateOrCreate(
            ['seller_id' => $user->id],
            $data
        );

        return response()->json([
            'message' => 'Store profile saved.',
            'data' => new StoreResource($store),
        ]);
    }

    private function deletePublicFile(?string $path): void
    {
        if ($path && ! str_starts_with($path, '/') && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
