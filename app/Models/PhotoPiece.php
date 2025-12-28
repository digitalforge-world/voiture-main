<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoPiece extends Model
{
    use HasFactory;

    protected $table = 'photos_pieces';

    public $timestamps = false;

    protected $fillable = ['piece_id', 'url', 'ordre', 'principale', 'date_ajout'];

    protected $casts = [
        'principale' => 'boolean',
        'date_ajout' => 'datetime',
    ];

    public function piece()
    {
        return $this->belongsTo(PieceDetachee::class, 'piece_id');
    }
}
