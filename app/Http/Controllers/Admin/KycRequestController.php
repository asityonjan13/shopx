<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Services\AlertService;
use App\Services\MailService;
use GuzzleHttp\Middleware;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllersMiddleware;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KycRequestController extends Controller implements HasMiddleware
{
    static function Middleware() : array
    {
        return [
            new ControllersMiddleware('permission:KYC Management')
        ];
    }

    function index(): View
    {
        $kycRequests = Kyc::paginate(25);
        return view('admin.kyc.index', compact('kycRequests'));
    }

    function pending(): View
    {
        $kycRequests = Kyc::where('status', 'pending')->paginate(25);
        return view('admin.kyc.pending', compact('kycRequests'));
    }

    function rejected(): View
    {
        $kycRequests = Kyc::where('status', 'rejected')->paginate(25);
        return view('admin.kyc.rejected', compact('kycRequests'));
    }

    function approved(): View
    {
        $kycRequests = Kyc::where('status', 'approved')->paginate(25);
        return view('admin.kyc.approved', compact('kycRequests'));
    }

    function show(Kyc $kyc_request): View
    {
        return view('admin.kyc.show', compact('kyc_request'));
    }


    function download(Kyc $kyc_request): StreamedResponse
    {
        return Storage::disk('private')->download($kyc_request->document_scan_copy);
    }


    public function update(Kyc $kyc_request, Request $request)
    {
        // dd($request->status);
        $request->validate(['status' => 'required|in:pending,approved,rejected']);

        $kyc_request->update(['status' => $request->status]);

        // Send email based on KYC status
        if ($kyc_request->status === 'approved') {
            MailService::send(
                to: $kyc_request->user->email,
                subject: 'Dear user your KYC application has been approved',
                body: 'Congratulation! you can now use additional features.'
            );
        } elseif ($kyc_request->status === 'pending') {
            MailService::send(
                to: $kyc_request->user->email,
                subject: 'Your KYC application is under review',
                body: 'Thank you for submitting your KYC documents. Your application is currently being reviewed. We will notify you once the verification is complete.'
            );
        } elseif ($kyc_request->status === 'rejected') {
            MailService::send(
                to: $kyc_request->user->email,
                subject: 'Your KYC application has been rejected',
                body: 'Unfortunately, your KYC application could not be approved. Please review the requirements and resubmit your documents. If you have any questions, please contact our support team.'
            );
        }


        return redirect()->back()->with(
            AlertService::created('KYC status updated successfully!')
        );
    }
}
