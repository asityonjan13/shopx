@extends('admin.layouts.app')

@section('contents')
    <div class="container-xl">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <a href="#" class="text-muted me-3" style="text-decoration: none; transition: all 0.2s;"
                    onmouseover="this.style.transform='translateX(-3px)'" onmouseout="this.style.transform='translateX(0)'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon">
                        <path d="M5 12l14 0"></path>
                        <path d="M5 12l6 6"></path>
                        <path d="M5 12l6 -6"></path>
                    </svg>
                </a>
                <h3 class="card-title m-0 flex-grow-1 text-center">All KYC Details</h3>
                <div style="width: 24px; margin-right: 1rem;"></div>
            </div>
            <div class="card-body p-0">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Date Of Birth</th>
                                    <th>Email</th>
                                    <th>Gender</th>
                                    <th>Status</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kycRequests as $kycRequest)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $kycRequest->full_name }}</td>
                                        <td class="text-secondary">{{ $kycRequest->date_of_birth }}</td>
                                        <td class="text-secondary">{{ $kycRequest->user->email }}</td>
                                        <td class="text-secondary">{{ $kycRequest->gender }}</td>
                                        @if ($kycRequest->status == 'pending')
                                            <td class="text-secondary">
                                                <span class="badge bg-warning-lt">{{ $kycRequest->status }}</span>
                                            </td>
                                        @elseif ($kycRequest->status == 'approved')
                                            <td class="text-secondary">
                                                <span class="badge bg-success-lt">{{ $kycRequest->status }}</span>
                                            </td>
                                        @else
                                            <td class="text-secondary">
                                                <span class="badge bg-danger-lt">{{ $kycRequest->status }}</span>
                                            </td>
                                        @endif
                                        <td>
                                            <a href="{{ route('admin.kyc.show', $kycRequest) }}">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        {{ $kycRequests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
