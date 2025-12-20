{{-- @props(['name', 'image'])

<div {{ $attributes->merge(['id'=>'image-preview']) }} style="background-image: url({{ $image }});">
    <label for="image-upload" id="image-label">Choose File</label>
    <input type="file" name="{{ $name }}" id="image-upload" />
</div> --}}

@props(['name','imageUploadId','imagePreviewId','imageLabelId','image'])

<div id="{{ $imagePreviewId }}" {{ $attributes->merge(['class'=>'text-dark image-preview']) }}
     @if($image && $image !== asset('')) style="background-image: url({{ $image }});" @endif>
    <label for="{{ $imageUploadId }}" id="{{ $imageLabelId }}">Choose File</label>
    <input type="file" name="{{ $name }}" id="{{ $imageUploadId }}" />
</div>
