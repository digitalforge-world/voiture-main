<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Driver extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'drivers';

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'photo',
        'vehicule_marque',
        'vehicule_modele',
        'vehicule_immatriculation',
        'vehicule_couleur',
        'statut',
        'identifiant',
        'mot_de_passe',
    ];

    /**
     * Mutator to hash password automatically.
     */
    public function setMotDePasseAttribute($value): void
    {
        if ($value === null || $value === '') {
            return;
        }
        $this->attributes['mot_de_passe'] = \Illuminate\Support\Facades\Hash::needsRehash($value) ? \Illuminate\Support\Facades\Hash::make($value) : $value;
    }

    /**
     * Get the driver's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->prenom} {$this->nom}");
    }

    /**
     * Get the transport reservations assigned to this driver.
     */
    public function reservations()
    {
        return $this->hasMany(ReservationTransport::class, 'driver_id');
    }
}
