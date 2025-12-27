<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EchangePiece extends Model
{
    use HasFactory;

    protected $table = 'echanges_pieces';

    protected $fillable = [
        'reference','tracking_number','user_id','piece_ancienne_nom','piece_ancienne_description','piece_ancienne_etat','piece_souhaitee_id','marque_vehicule','modele_vehicule','annee_vehicule','photos','statut','rabais_propose','commentaire_admin'
    ];

    public const CREATED_AT = 'date_demande';
    public const UPDATED_AT = 'date_modification';

    protected $casts = [
        'rabais_propose' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pieceSouhaitee()
    {
        return $this->belongsTo(PieceDetachee::class, 'piece_souhaitee_id');
    }
}
