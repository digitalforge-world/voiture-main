<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voiture extends Model
{
    use HasFactory;

    protected $table = 'voitures';

    protected $fillable = [
        'marque','modele','annee','kilometrage','prix','pays_origine','ville_origine','etat','moteur','cylindree','puissance','carburant','transmission','couleur','nombre_portes','nombre_places','disponibilite','type_vehicule','description','options_equipements','numero_chassis','port_recommande_id'
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'kilometrage' => 'integer',
    ];

    public function portRecommande()
    {
        return $this->belongsTo(Port::class, 'port_recommande_id');
    }

    public function photos()
    {
        return $this->hasMany(PhotoVoiture::class, 'voiture_id');
    }

    public function commandes()
    {
        return $this->hasMany(CommandeVoiture::class, 'voiture_id');
    }
}
