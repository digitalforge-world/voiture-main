<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandeVoiture extends Model
{
    use HasFactory;

    protected $table = 'commandes_voitures';

    protected $fillable = [
        'reference','tracking_number','user_id','voiture_id','port_destination_id','prix_voiture','frais_import','frais_port','frais_douane','autres_frais','montant_total','acompte_verse','reste_a_payer','statut','date_commande','date_confirmation','date_paiement_complet','date_expedition','date_livraison_estimee','date_livraison_reelle','notes','notes_admin'
    ];

    public const CREATED_AT = 'date_commande';
    public const UPDATED_AT = 'date_modification';

    protected $casts = [
        'prix_voiture' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'acompte_verse' => 'decimal:2',
        'reste_a_payer' => 'decimal:2',
        'date_commande' => 'datetime',
        'date_modification' => 'datetime',
        'date_confirmation' => 'datetime',
        'date_paiement_complet' => 'datetime',
        'date_expedition' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function voiture()
    {
        return $this->belongsTo(Voiture::class, 'voiture_id');
    }

    public function portDestination()
    {
        return $this->belongsTo(Port::class, 'port_destination_id');
    }

    public function port()
    {
        return $this->portDestination();
    }
}
