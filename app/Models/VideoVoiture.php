<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoVoiture extends Model
{
    protected $table = 'videos_voitures';

    protected $fillable = [
        'voiture_id',
        'url',
        'ordre'
    ];

    public function voiture()
    {
        return $this->belongsTo(Voiture::class);
    }
}
