<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Voiture extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'voitures';

    protected $fillable = [
        'marque',
        'modele',
        'annee',
        'kilometrage',
        'prix',
        'pays_origine',
        'ville_origine',
        'etat',
        'moteur',
        'cylindree',
        'puissance',
        'carburant',
        'transmission',
        'couleur',
        'nombre_portes',
        'nombre_places',
        'disponibilite',
        'type_vehicule',
        'description',
        'options_equipements',
        'numero_chassis',
        'port_recommande_id',
        'photo_principale',
        'consommation_mixte',
        'emission_co2',
        'vitesse_max',
        'acceleration_0_100',
        'couple_moteur',
        'capacite_reservoir',
        'poids_a_vide',
        'origine_marche',
        'nombre_proprietaires',
        'carnet_entretien_ajour',
        'non_fumeur',
        'classe_environnementale',
        'equipements_details'
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'kilometrage' => 'integer',
        'carnet_entretien_ajour' => 'boolean',
        'non_fumeur' => 'boolean',
        'equipements_details' => 'array',
    ];

    public function portRecommande()
    {
        return $this->belongsTo(Port::class, 'port_recommande_id');
    }

    public function photos()
    {
        return $this->hasMany(PhotoVoiture::class, 'voiture_id');
    }

    public function videos()
    {
        return $this->hasMany(VideoVoiture::class, 'voiture_id');
    }

    public function commandes()
    {
        return $this->hasMany(CommandeVoiture::class, 'voiture_id');
    }
}
