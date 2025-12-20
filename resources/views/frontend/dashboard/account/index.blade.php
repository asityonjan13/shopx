@extends('frontend.dashboard.dashboard-app')

@section('dashboard_contents')
    <div class="dashboard-content-wrapper">
        <div class="d-flex flex-column align-items-center gap-3">
            <div class="card w-100" style="max-width: 800px;">
                <div class="card-header">
                    <h5 class="mb-0">Basic Account Details</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Full Name <span class="required">*</span></label>
                                    <input value="{{ auth('web')->user()->name }}" required class="form-control"
                                        name="name" type="text" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                <div>
                                    <label class="form-label">Email Address <span class="required">*</span></label>
                                    <input value="{{ auth('web')->user()->email }}" required class="form-control"
                                        name="email" type="email" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <p class="form-label mb-2">Profile Picture<span class="required">*</span></p>
                                <x-input-image
                                imageUploadId="image-upload"
                                imagePreviewId="image-preview"
                                imageLabelId="image-label"
                                name="avatar"
                                :image="asset(auth('web')->user()->avatar)" />
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-fill-out submit font-weight-bold" name="submit"
                                    value="Submit">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card w-100" style="max-width: 800px;">
                <div class="card-header">
                    <h5 class="mb-0">Change Password</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')
                        <div class="row g-3">

                            <div class="col-md-12">
                                <label class="form-label">Current Password <span class="required">*</span></label>
                                <input required class="form-control" name="current_password" type="password" />
                                <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">New Password <span class="required">*</span></label>
                                <input required class="form-control" name="password" type="password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Confirm Password <span class="required">*</span></label>
                                <input required class="form-control" name="password_confirmation" type="password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-fill-out submit font-weight-bold" name="submit"
                                    value="Submit">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.uploadPreview({
                input_field: "#image-upload", // Default: .image-upload
                preview_box: "#image-preview", // Default: .image-preview
                label_field: "#image-label", // Default: .image-label
                label_default: "Choose File", // Default: Choose File
                label_selected: "Change File", // Default: Change File
                no_label: false // Default: false
            });
        });
    </script>
@endpush
