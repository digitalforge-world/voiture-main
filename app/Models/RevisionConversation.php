<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisionConversation extends Model
{
    use HasFactory;

    protected $table = 'revision_conversations';
    
    // UUID is not auto-incrementing
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'client_nom',
        'client_telephone',
        'client_email',
        'marque_vehicule',
        'modele_vehicule',
        'annee_vehicule',
        'messages',
        'summary',
        'is_closed',
        'revision_id'
    ];

    protected $casts = [
        'messages' => 'array',
        'is_closed' => 'boolean',
    ];

    public function revision()
    {
        return $this->belongsTo(Revision::class, 'revision_id');
    }
}
