<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneCommandePiece extends Model
{
    use HasFactory;

    protected $table = 'lignes_commandes_pieces';

    protected $fillable = ['commande_piece_id','piece_id','quantite','prix_unitaire','montant_ligne'];

    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire' => 'decimal:2',
        'montant_ligne' => 'decimal:2',
    ];

    public function commande()
    {
        return $this->belongsTo(CommandePiece::class, 'commande_piece_id');
    }

    public function piece()
    {
        return $this->belongsTo(PieceDetachee::class, 'piece_id');
    }
}
