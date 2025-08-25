<?php

namespace App\Services\Craftsman;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CraftsmanImageService
{
    protected string $disk = 'public';
    protected string $directory = 'craftsmen/avatars';

    /**
     * Store a new avatar for a craftsman
     */
    public function storeAvatar(UploadedFile $file, int $userId): array
    {
        $filename = $this->generateFilename($file, $userId);
        $path = $file->storeAs($this->directory, $filename, $this->disk);

        return [
            'path' => $path,
            'filename' => $filename,
            'url' => Storage::disk($this->disk)->url($path)
        ];
    }

    /**
     * Update an existing avatar for a craftsman
     */
    public function updateAvatar(UploadedFile $file, int $userId, ?string $oldAvatar = null): array
    {
        // Delete old avatar if it exists
        if ($oldAvatar) {
            $this->deleteAvatar($oldAvatar);
        }

        // Store new avatar
        return $this->storeAvatar($file, $userId);
    }

    /**
     * Delete an avatar
     */
    public function deleteAvatar(?string $avatarPath): bool
    {
        if (!$avatarPath) {
            return true;
        }

        try {
            if (Storage::disk($this->disk)->exists($avatarPath)) {
                Storage::disk($this->disk)->delete($avatarPath);
                return true;
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Generate a unique filename for the avatar
     */
    protected function generateFilename(UploadedFile $file, int $userId): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->timestamp;
        $random = Str::random(8);
        
        return "user_{$userId}_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Get the full URL for an avatar
     */
    public function getAvatarUrl(?string $avatarPath): ?string
    {
        if (!$avatarPath) {
            return null;
        }

        return Storage::disk($this->disk)->url($avatarPath);
    }
}
