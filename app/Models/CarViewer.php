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

        $extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $pad = str_pad(1, strlen((string) $this->frame_count), '0', STR_PAD_LEFT);

        foreach ($extensions as $ext) {
            $path = "{$this->frames_path}/frame_{$pad}.{$ext}";
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                return \Illuminate\Support\Facades\Storage::url($path);
            }
        }
        return null;
    }
}
