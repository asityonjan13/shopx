<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\KycStoreRequest;
use App\Http\Resources\KycResource;
use App\Models\Kyc;
use App\Traits\FileUploadTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class KycController extends Controller
{
    use FileUploadTrait;

    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $request->user()->kyc ? new KycResource($request->user()->kyc) : null,
        ]);
    }

    public function store(KycStoreRequest $request): JsonResponse
    {
        $user = $request->user();
        $kyc = $user->kyc;

        if ($kyc && in_array($kyc->status, ['pending', 'approved'], true)) {
            throw ValidationException::withMessages([
                'status' => $kyc->status === 'approved'
                    ? 'Your identity is already verified.'
                    : 'Your application is already waiting for review.',
            ]);
        }

        if ($kyc && $kyc->status === 'rejected' && $kyc->document_scan_copy) {
            Storage::disk('private')->delete($kyc->document_scan_copy);
        }

        $kyc ??= new Kyc();
        $kyc->fill([
            'user_id' => $user->id,
            'full_name' => $request->string('full_name')->toString(),
            'date_of_birth' => $request->date('date_of_birth')->toDateString(),
            'gender' => $request->string('gender')->toString(),
            'full_address' => $request->string('full_address')->toString(),
            'document_type' => $request->string('document_type')->toString(),
            'document_scan_copy' => $this->uploadPrivateFile($request->file('document_scan_copy')),
            'status' => 'pending',
            'rejected_reason' => null,
            'verified_at' => null,
        ]);
        $kyc->save();

        return response()->json([
            'message' => 'KYC submitted. We will review it shortly.',
            'data' => new KycResource($kyc),
        ], 201);
    }
}
