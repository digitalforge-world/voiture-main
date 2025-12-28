<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoPiece extends Model
{
    protected $table = 'videos_pieces';

    protected $fillable = [
        'piece_id',
        'url',
        'ordre'
    ];

    public function piece()
    {
        return $this->belongsTo(PieceDetachee::class, 'piece_id');
    }
}
