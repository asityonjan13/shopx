@extends('vendorend.layouts.app')

@section('contents')
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Update Store Profile</h3>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('vendor.store-profile.update', 1) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Full Name</label>
                                <input type="text" class="form-control" name="name" placeholder=""
                                    value="{{ $store?->name }}">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Shop Address</label>
                                <input type="text" class="form-control" name="address" placeholder=""
                                    value="{{ $store?->address }}">
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="form-label mb-2">Shop Logo<span class="required">*</span></p>
                                <x-input-image imageUploadId="image-upload" imagePreviewId="image-preview"
                                    imageLabelId="image-label" name="logo" image="{{ asset($store?->logo) }}" />
                                <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="form-label mb-2">Shop Banner<span class="required">*</span></p>
                                <x-input-image imageUploadId="image-upload2" imagePreviewId="image-preview2"
                                    imageLabelId="image-label2" name="banner" image="{{ asset($store?->banner) }}" />
                                <x-input-error :messages="$errors->get('banner')" class="mt-2" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Email</label>
                                <input type="email" class="form-control" name="email" placeholder=""
                                    value="{{ $store?->email }}">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Phone</label>
                                <input type="tel" class="form-control" name="phone" placeholder="e.g. 9812345678"
                                    value="{{ $store?->phone }}">
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label required">Short Description</label>
                                <textarea rows="6" class="form-control" name="short_description" placeholder="">{{ $store?->short_description }}</textarea>
                                <x-input-error :messages="$errors->get('short_description')" class="mt-2" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label required">Long Description</label>
                                <textarea class="tinymce form-control" rows="6" name="long_description" placeholder="">{{ $store?->long_description }}</textarea>
                                <x-input-error :messages="$errors->get('long_description')" class="mt-2" />
                            </div>
                        </div>
                        <div class="text-start">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.uploadPreview({
                input_field: "#image-upload",
                preview_box: "#image-preview",
                label_field: "#image-label",
                label_default: "Choose File",
                label_selected: "Change File",
                no_label: false
            });
            $.uploadPreview({
                input_field: "#image-upload2",
                preview_box: "#image-preview2",
                label_field: "#image-label2",
                label_default: "Choose File",
                label_selected: "Change File",
                no_label: false
            });
        });
    </script>
@endpush
