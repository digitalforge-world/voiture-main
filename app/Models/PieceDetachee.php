<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PieceDetachee extends Model
{
    use HasFactory;

    protected $table = 'pieces_detachees';

    protected $fillable = [
        'nom',
        'reference',
        'marque_compatible',
        'modele_compatible',
        'annee_debut',
        'annee_fin',
        'moteur_compatible',
        'numero_chassis_compatible',
        'categorie',
        'sous_categorie',
        'prix',
        'stock',
        'stock_minimum',
        'etat',
        'origine',
        'description',
        'specifications',
        'compatible_avec',
        'image',
        'poids',
        'dimensions',
        'garantie_mois',
        'disponible'
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'stock' => 'integer',
        'stock_minimum' => 'integer',
        'disponible' => 'boolean',
    ];

    public function lignesCommandes()
    {
        return $this->hasMany(LigneCommandePiece::class, 'piece_id');
    }

    public function photos()
    {
        return $this->hasMany(PhotoPiece::class, 'piece_id');
    }
}
