<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SettingUpdateRequest;
use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function show(): JsonResponse
    {
        return response()->json([
            'data' => [
                'site_name' => Setting::where('key', 'site_name')->value('value') ?? 'ShopX',
                'site_email' => Setting::where('key', 'site_email')->value('value'),
                'site_phone' => Setting::where('key', 'site_phone')->value('value'),
            ],
        ]);
    }

    public function update(SettingUpdateRequest $request, SettingService $settings): JsonResponse
    {
        foreach ($request->validated() as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $settings->clearCachedSettings();
        $settings->setSettings();

        return response()->json([
            'message' => 'Settings saved.',
            'data' => $request->validated(),
        ]);
    }
}
