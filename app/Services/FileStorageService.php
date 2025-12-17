<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileStorageService
{
    public function storeCourseThumbnail(UploadedFile $file): string
    {
        return $file->store('courses', 'public');
    }

    public function replaceCourseThumbnail(?string $oldPath, UploadedFile $newFile
    ): string {
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        return $newFile->store('courses', 'public');
    }
}