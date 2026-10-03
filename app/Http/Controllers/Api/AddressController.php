<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return AddressResource::collection(
            $request->user()->addresses()->latest()->get()
        )->response();
    }

    public function store(AddressRequest $request): JsonResponse
    {
        $address = $request->user()->addresses()->create($this->payload($request));
        $this->syncDefault($request, $address);

        return response()->json([
            'message' => 'Address saved.',
            'data' => new AddressResource($address->fresh()),
        ], 201);
    }

    public function update(AddressRequest $request, Address $address): JsonResponse
    {
        abort_unless($address->user_id === $request->user()->id, 404);
        $address->update($this->payload($request));
        $this->syncDefault($request, $address);

        return response()->json([
            'message' => 'Address updated.',
            'data' => new AddressResource($address->fresh()),
        ]);
    }

    public function destroy(Request $request, Address $address): JsonResponse
    {
        abort_unless($address->user_id === $request->user()->id, 404);
        $address->delete();

        return response()->json(['message' => 'Address removed.']);
    }

    private function payload(AddressRequest $request): array
    {
        return [
            'label' => $request->input('label', 'Home'),
            'full_name' => $request->string('full_name')->toString(),
            'phone' => $request->string('phone')->toString(),
            'line1' => $request->string('line1')->toString(),
            'line2' => $request->input('line2'),
            'city' => $request->string('city')->toString(),
            'state' => $request->input('state'),
            'postal_code' => $request->string('postal_code')->toString(),
            'country' => $request->string('country')->toString(),
            'is_default' => $request->boolean('is_default'),
        ];
    }

    private function syncDefault(Request $request, Address $address): void
    {
        if (! $address->is_default) {
            return;
        }

        $request->user()->addresses()
            ->where('id', '!=', $address->id)
            ->update(['is_default' => false]);
    }
}
