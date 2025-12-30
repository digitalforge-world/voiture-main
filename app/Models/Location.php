<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Location extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'locations';

    protected $fillable = [
        'user_id',
        'reference',
        'tracking_number',
        'client_nom',
        'client_email',
        'client_telephone',
        'voiture_location_id',
        'date_debut',
        'date_fin',
        'date_debut_reelle',
        'date_fin_reelle',
        'montant_location',
        'caution',
        'frais_supplementaires',
        'montant_total',
        'statut',
        'etat_depart',
        'kilometrage_depart',
        'etat_retour',
        'kilometrage_retour',
        'commentaires'
    ];

    public const CREATED_AT = 'date_reservation';
    public const UPDATED_AT = 'date_modification';

    protected $casts = [
        'montant_location' => 'decimal:2',
        'caution' => 'decimal:2',
        'frais_supplementaires' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'date_reservation' => 'datetime',
        'date_modification' => 'datetime',
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'date_debut_reelle' => 'datetime',
        'date_fin_reelle' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function voiture()
    {
        return $this->belongsTo(VoitureLocation::class, 'voiture_location_id');
    }
}
