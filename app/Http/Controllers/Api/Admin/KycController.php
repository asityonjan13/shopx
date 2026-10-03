<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\KycReviewRequest;
use App\Http\Resources\KycResource;
use App\Models\Kyc;
use App\Services\MailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KycController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Kyc::query()->with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        return KycResource::collection($query->paginate(20))->response();
    }

    public function show(Kyc $kyc): KycResource
    {
        return new KycResource($kyc->load('user'));
    }

    public function update(KycReviewRequest $request, Kyc $kyc): JsonResponse
    {
        $status = $request->string('status')->toString();
        $kyc->update([
            'status' => $status,
            'rejected_reason' => $status === 'rejected' ? $request->input('rejected_reason') : null,
            'verified_at' => $status === 'approved' ? now() : null,
        ]);

        $subject = match ($status) {
            'approved' => 'Your ShopX identity check was approved',
            'rejected' => 'Your ShopX identity check needs another look',
            default => 'Your ShopX identity check is in review',
        };
        $body = match ($status) {
            'approved' => 'You can now publish products from your vendor dashboard.',
            'rejected' => 'Reason: '.($kyc->rejected_reason ?: 'Please resubmit your documents.'),
            default => 'We are reviewing the documents you sent.',
        };

        if ($kyc->user?->email) {
            MailService::send($kyc->user->email, $subject, $body);
        }

        return response()->json([
            'message' => 'KYC status updated.',
            'data' => new KycResource($kyc->load('user')),
        ]);
    }

    public function download(Kyc $kyc): StreamedResponse
    {
        abort_unless($kyc->document_scan_copy && Storage::disk('private')->exists($kyc->document_scan_copy), 404);

        return Storage::disk('private')->download($kyc->document_scan_copy);
    }
}
