@extends('frontend.layouts.app')

@section('contents')
<div class="page-content pt-20 pb-30">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-10 col-md-12 m-auto">
                <div class="row">
                    <div class="col-lg-8 col-md-10 offset-lg-2">

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <div class="login_wrap widget-taber-content background-white">
                            <div class="padding_eight_all bg-white">

                                <div class="heading_s1 mb-4">
                                    <h4 class="mb-5">KYC Verification</h4>
                                    <p class="text-muted">Please provide your information for identity verification</p>
                                </div>

                                <form method="POST" action="{{ route('kyc.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <!-- Full Name -->
                                    <div class="form-group">
                                        <label for="full_name">Full Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                               id="full_name"
                                               name="full_name"
                                               class="form-control @error('full_name') is-invalid @enderror"
                                               value="{{ old('full_name') }}"
                                               placeholder="Enter your full name"
                                               required
                                               autofocus />
                                        <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                                    </div>

                                    <!-- Date Of Birth -->
                                    <div class="form-group">
                                        <label for="date_of_birth">Date of Birth <span class="text-danger">*</span></label>
                                        <input type="text"
                                               id="date_of_birth"
                                               name="date_of_birth"
                                               class="datepicker"
                                               placeholder="1997/04/01"
                                               value="{{ old('date_of_birth') }}"
                                               required />
                                        <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                                    </div>

                                    <!-- Gender -->
                                    <div class="form-group">
                                        <label for="gender">Gender <span class="text-danger">*</span></label>
                                        <select id="gender"
                                                name="gender"
                                                class="form-control @error('gender') is-invalid @enderror"
                                                required>
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                                    </div>

                                    <!-- Full Address -->
                                    <div class="form-group">
                                        <label for="full_address">Full Address <span class="text-danger">*</span></label>
                                        <textarea id="full_address"
                                                  name="full_address"
                                                  class="form-control @error('full_address') is-invalid @enderror"
                                                  rows="3"
                                                  placeholder="Enter your complete address"
                                                  required>{{ old('full_address') }}</textarea>
                                        <x-input-error :messages="$errors->get('full_address')" class="mt-2" />
                                    </div>

                                    <!-- Document Type -->
                                    <div class="form-group">
                                        <label for="document_type">Document Type <span class="text-danger">*</span></label>
                                        <select id="document_type"
                                                name="document_type"
                                                class="form-control @error('document_type') is-invalid @enderror"
                                                required>
                                            <option value="">Select Document Type</option>
                                            <option value="passport" {{ old('document_type') == 'passport' ? 'selected' : '' }}>Passport</option>
                                            <option value="driving_license" {{ old('document_type') == 'driving_license' ? 'selected' : '' }}>Driving License</option>
                                            <option value="id_card" {{ old('document_type') == 'id_card' ? 'selected' : '' }}>ID Card</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('document_type')" class="mt-2" />
                                    </div>

                                    <!-- Document Scan Copy -->
                                    <div class="form-group">
                                        <label for="document_scan_copy">Document Scan Copy <span class="text-danger">*</span></label>
                                        {{-- <x-input-image id="document_scan_copy" name="document_scan_copy" :image="asset(auth('web')->user()->document_scan_copy)" /> --}}
                                        <input type="file"
                                               id="document_scan_copy"
                                               name="document_scan_copy"
                                               class="form-control @error('document_scan_copy') is-invalid @enderror"
                                               accept="image/*,application/pdf"
                                               required />
                                        <small class="form-text text-muted">Upload a clear scan or photo of your document (JPG, PNG, or PDF, Max: 2MB)</small>
                                        <x-input-error :messages="$errors->get('document_scan_copy')" class="mt-2" />
                                    </div>

                                    <!-- Terms and Conditions -->
                                    <div class="form-group mb-30">
                                        <div class="chek-form">
                                            <div class="custome-checkbox">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       id="terms"
                                                       name="terms"
                                                       required>
                                                <label class="form-check-label" for="terms">
                                                    <span>I agree that the information provided is accurate and complete</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-heading btn-block hover-up">
                                            Submit for Verification
                                        </button>
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
@endsection
