<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandePiece extends Model
{
    use HasFactory;

    protected $table = 'commandes_pieces';

    protected $fillable = [
        'reference','tracking_number','user_id','montant_total','statut','type_livraison','adresse_livraison','frais_livraison','date_commande','date_livraison_estimee','date_livraison_reelle','notes'
    ];

    public const CREATED_AT = 'date_commande';
    public const UPDATED_AT = 'date_modification';

    protected $casts = [
        'montant_total' => 'decimal:2',
        'frais_livraison' => 'decimal:2',
        'date_commande' => 'datetime',
        'date_modification' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lignes()
    {
        return $this->hasMany(LigneCommandePiece::class, 'commande_piece_id');
    }
}
