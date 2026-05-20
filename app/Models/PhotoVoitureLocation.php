<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoVoitureLocation extends Model
{
    use HasFactory;

    protected $table = 'photos_voitures_location';

    public $timestamps = false;

    protected $fillable = ['voiture_location_id', 'url', 'ordre', 'principale', 'date_ajout'];

    protected $casts = [
        'principale' => 'boolean',
        'date_ajout' => 'datetime',
    ];

    public function voitureLocation()
    {
        return $this->belongsTo(VoitureLocation::class, 'voiture_location_id');
    }
}
