<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    use HasFactory;

    protected $table = 'revisions';

    protected $fillable = [
        'reference',
        'tracking_number',
        'client_nom',
        'client_email',
        'client_telephone',
        'user_id',
        'marque_vehicule',
        'modele_vehicule',
        'annee_vehicule',
        'immatriculation',
        'kilometrage',
        'probleme_description',
        'type_revision',
        'diagnostic',
        'interventions_prevues',
        'pieces_necessaires',
        'montant_devis',
        'montant_final',
        'statut',
        'photos',
        'notes'
    ];

    public const CREATED_AT = 'date_demande';
    public const UPDATED_AT = 'date_modification';

    protected $casts = [
        'montant_devis' => 'decimal:2',
        'montant_final' => 'decimal:2',
        'date_demande' => 'datetime',
        'date_modification' => 'datetime',
        'date_diagnostic' => 'datetime',
        'date_devis' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
