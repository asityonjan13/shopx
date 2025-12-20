<?php
namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait FileUploadTrait
{
    public function uploadFile(UploadedFile $file, ?string $old_path = null, ?string $path = 'uploads'): ?string
    {
        if (!$file->isValid()) {
            return null;
        }

        $ignore_path = ['/defaults/avatar.png', '/default/banner.png', '/defaults/logo.png'];
        if ($old_path && File::exists(public_path($old_path)) && !in_array($old_path, $ignore_path)) {
            File::delete(public_path($old_path));
        }

        $folderPath = public_path($path);
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move($folderPath, $filename);
        $filepath = $path . '/' . $filename;

        return $filepath;
    }

    public function uploadPrivateFile(UploadedFile $file, ?string $old_path = null, ?string $path = 'documents'): ?string
    {
        if (!$file->isValid()) {
            return null;
        }

        // Delete old file if exists
        if ($old_path && Storage::disk('private')->exists($old_path)) {
            Storage::disk('private')->delete($old_path);
        }

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs($path, $filename, 'private');

        return $filePath;
    }

    public function deletePrivateFile(?string $path): bool
    {
        if ($path && Storage::disk('private')->exists($path)) {
            return Storage::disk('private')->delete($path);
        }

        return false;
    }
}
