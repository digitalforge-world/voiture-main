<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoitureLocation extends Model
{
    use HasFactory;

    protected $table = 'voitures_location';

    protected $fillable = [
        'marque','modele','annee','immatriculation','couleur','kilometrage','transmission','carburant','nombre_places','prix_jour','caution','disponible','categorie','description','equipements','photo_principale','etat_general'
    ];

    protected $casts = [
        'prix_jour' => 'decimal:2',
        'caution' => 'decimal:2',
        'disponible' => 'boolean',
    ];

    public function locations()
    {
        return $this->hasMany(Location::class, 'voiture_location_id');
    }
}
