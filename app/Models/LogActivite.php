<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivite extends Model
{
    use HasFactory;

    protected $table = 'logs_activites';

    protected $fillable = ['user_id', 'action', 'table_concernee', 'enregistrement_id', 'details', 'ip_address', 'user_agent', 'date_action'];

    public const CREATED_AT = 'date_action';
    public const UPDATED_AT = null;
    protected $casts = [
        'date_action' => 'datetime',
        'details' => 'array'
    ];

    protected $appends = ['operator_name'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getOperatorNameAttribute()
    {
        if ($this->user) {
            return $this->user->prenom . ' ' . $this->user->nom;
        }

        $details = $this->details;

        // Handle Creation (details is directly the attributes)
        if (is_array($details)) {
            if (!empty($details['client_nom'])) {
                return 'Client: ' . $details['client_nom'];
            }
            if (!empty($details['nom']) && !empty($details['prenom'])) {
                return 'Client: ' . $details['prenom'] . ' ' . $details['nom'];
            }
            // Sometimes it might be nested or different structure depending on model
        }

        return 'Visiteur (Public)';
    }
}
