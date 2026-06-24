<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'categorie',
        'description',
        'options_equipements',
        'numero_chassis',
        'port_recommande_id',
        'photo_principale',
        'model_3d',
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
        'equipements_details',
        'slug'
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'kilometrage' => 'integer',
        'carnet_entretien_ajour' => 'boolean',
        'non_fumeur' => 'boolean',
        'equipements_details' => 'array',
    ];

    /**
     * Utilise le slug au lieu de l'ID dans les URLs.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Génère automatiquement un slug unique à la création.
     */
    protected static function booted(): void
    {
        static::creating(function (Voiture $voiture) {
            if (empty($voiture->slug)) {
                $voiture->slug = self::generateUniqueSlug($voiture);
            }
        });
    }

    /**
     * Génère un slug unique : marque-modele-annee-xyz123
     */
    public static function generateUniqueSlug(Voiture $voiture): string
    {
        $base = Str::slug($voiture->marque . '-' . $voiture->modele . '-' . $voiture->annee);
        $suffix = Str::lower(Str::random(6));
        $slug = $base . '-' . $suffix;

        // S'assurer de l'unicité
        while (self::where('slug', $slug)->exists()) {
            $suffix = Str::lower(Str::random(6));
            $slug = $base . '-' . $suffix;
        }

        return $slug;
    }

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
