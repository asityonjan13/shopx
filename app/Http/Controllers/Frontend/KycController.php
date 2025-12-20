<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Services\AlertService;
use App\Traits\FileUploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\RedirectResponse;

class KycController extends Controller
{
    use FileUploadTrait;

    function index(): View | RedirectResponse
    {
        if(auth('web')->user()->kyc?->status == 'approved'|| auth('web')->user()->kyc?->status == 'pending'){
            return redirect()->route('vendor.dashboard');
        }
        return view('frontend.pages.kyc');
    }

    function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'max:255', 'string'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female,other'],
            'full_address' => ['required', 'string', 'max:500'],
            'document_type' => ['required', 'in:passport,driving_license,id_card'],
            'document_scan_copy' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $user = auth('web')->user();
        // $kyc = Kyc::where('user_id', $user->id)->first();
        $kyc = auth('web')->user()->kyc; //can write because we have made user hasone to one relationship with kyc in user model

        if ($kyc) {
            if ($kyc->status === 'pending') {
                return redirect()->route('vendor.dashboard')->with(
                    AlertService::error('You have already submitted your KYC. Please wait for admin approval.')
                );
            } elseif ($kyc->status === 'approved') {
                return redirect()->route('vendor.dashboard')->with(
                    AlertService::error('Your KYC is already approved.')
                );
            }
            // If rejected, delete old document before uploading new one
            if ($kyc->status === 'rejected' && $kyc->document_scan_copy) {
                Storage::disk('private')->delete($kyc->document_scan_copy);
            }
        } else {
            $kyc = new Kyc();
        }

        // Upload new document
        $filePath = $this->uploadPrivateFile($validated['document_scan_copy']);

        // Update KYC record
        $kyc->user_id = $user->id;
        $kyc->full_name = $validated['full_name'];
        $kyc->date_of_birth = $validated['date_of_birth'];
        $kyc->gender = $validated['gender'];
        $kyc->full_address = $validated['full_address'];
        $kyc->document_type = $validated['document_type'];
        $kyc->document_scan_copy = $filePath;
        $kyc->status = 'pending';
        $kyc->save();

        return redirect()->route('vendor.dashboard')->with(
            AlertService::created('KYC submission successful. We will review your application shortly.')
        );
    }
}
