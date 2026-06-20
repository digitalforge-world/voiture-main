<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportConversation extends Model
{
    public $timestamps = false;

    protected $table = 'transport_conversations';

    protected $fillable = [
        'reservation_transport_id',
        'auteur',
        'message',
        'type',
        'montant',
    ];

    protected $casts = [
        'montant'    => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function reservation()
    {
        return $this->belongsTo(ReservationTransport::class, 'reservation_transport_id');
    }
}
