<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoVoiture extends Model
{
    use HasFactory;

    protected $table = 'photos_voitures';

    public $timestamps = false;

    protected $fillable = ['voiture_id','url','ordre','principale','date_ajout'];

    protected $casts = ['principale' => 'boolean'];

    public function voiture()
    {
        return $this->belongsTo(Voiture::class, 'voiture_id');
    }
}
