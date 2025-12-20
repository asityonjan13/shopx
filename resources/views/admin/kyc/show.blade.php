@extends('admin.layouts.app')

@section('contents')
    <div class="container-xl">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <a href="{{ url()->previous() }}" class="text-muted me-3" style="text-decoration: none; transition: all 0.2s;"
                    onmouseover="this.style.transform='translateX(-3px)'" onmouseout="this.style.transform='translateX(0)'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon">
                        <path d="M5 12l14 0"></path>
                        <path d="M5 12l6 6"></path>
                        <path d="M5 12l6 -6"></path>
                    </svg>
                </a>
                <h3 class="card-title m-0 flex-grow-1 text-center">KYC Detail</h3>
                <div style="width: 24px; margin-right: 1rem;"></div>
            </div>
            <div class="card-body p-0">
                <div class="card">
                    <div class="table-responsive">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="row g-0">
                                    <div class="col-md-6">
                                        <div class="p-3 border-bottom">
                                            <div class="row">
                                                <div class="col-5 text-muted">Full Name</div>
                                                <div class="col-7 fw-bold text-end">{{ $kyc_request->full_name }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border-bottom border-start">
                                            <div class="row">
                                                <div class="col-5 text-muted">Date of Birth</div>
                                                <div class="col-7 fw-bold text-end">{{ $kyc_request->date_of_birth }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border-bottom">
                                            <div class="row">
                                                <div class="col-5 text-muted">Email</div>
                                                <div class="col-7 fw-bold text-end">{{ $kyc_request->user->email }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border-bottom border-start">
                                            <div class="row">
                                                <div class="col-5 text-muted">Gender</div>
                                                <div class="col-7 fw-bold text-end">{{ $kyc_request->gender }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border-bottom">
                                            <div class="row">
                                                <div class="col-5 text-muted">Full Address</div>
                                                <div class="col-7 fw-bold text-end">{{ $kyc_request->full_address }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border-bottom border-start">
                                            <div class="row">
                                                <div class="col-5 text-muted">Document Type</div>
                                                <div class="col-7 fw-bold text-end">{{ $kyc_request->document_type }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border-bottom">
                                            <div class="row">
                                                <div class="col-5 text-muted">Status</div>
                                                <div class="col-7 text-end">
                                                    @if ($kyc_request->status == 'pending')
                                                        <span class="badge bg-warning-lt">{{ $kyc_request->status }}</span>
                                                    @elseif ($kyc_request->status == 'approved')
                                                        <span class="badge bg-success-lt">{{ $kyc_request->status }}</span>
                                                    @else
                                                        <span class="badge bg-danger-lt">{{ $kyc_request->status }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border-bottom border-start">
                                            <div class="row">
                                                <div class="col-5 text-muted">Document Scan Copy</div>
                                                <div class="col-7 text-end">
                                                    <a class="btn btn-primary btn-sm"
                                                        href="{{ route('admin.kyc.download', $kyc_request) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="icon">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                                            <path d="M7 11l5 5l5 -5" />
                                                            <path d="M12 4l0 12" />
                                                        </svg>
                                                        Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="p-3 border-bottom">
                                            <div class="row align-items-center">
                                                <div class="col-3 text-muted">Change Status</div>
                                                <div class="col-9 text-end">
                                                    <form action="{{ route('admin.kyc.update', $kyc_request) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="d-flex gap-2 align-items-center justify-content-end">
                                                            <select name="status" class="form-select" style="width: auto;">
                                                                <option value="pending"
                                                                    {{ $kyc_request->status == 'pending' ? 'selected' : '' }}>
                                                                    Pending
                                                                </option>
                                                                <option value="approved"
                                                                    {{ $kyc_request->status == 'approved' ? 'selected' : '' }}>
                                                                    Approved
                                                                </option>
                                                                <option value="rejected"
                                                                    {{ $kyc_request->status == 'rejected' ? 'selected' : '' }}>
                                                                    Rejected
                                                                </option>
                                                            </select>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
