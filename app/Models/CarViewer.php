<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarViewer extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'frame_count',
        'frames_path',
    ];

    protected $casts = [
        'frame_count' => 'integer',
    ];

    /**
     * URL de la première frame (thumbnail)
     */
    public function getThumbnailAttribute(): ?string
    {
        if (!$this->frames_path) return null;

        try {
            $files = \Illuminate\Support\Facades\Storage::disk('public')->files($this->frames_path);
            
            // Trouver la première frame triée par nom de fichier
            $firstFrame = collect($files)
                ->filter(fn($f) => preg_match('/frame_\d+\.(jpg|jpeg|png|webp)$/i', basename($f)))
                ->sortBy(fn($f) => basename($f))
                ->first();

            if ($firstFrame) {
                return \Illuminate\Support\Facades\Storage::url($firstFrame);
            }
        } catch (\Exception $e) {
            // Silence errors to avoid blocking the page
        }

        return null;
    }
}
