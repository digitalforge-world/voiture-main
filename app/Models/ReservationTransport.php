<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class ReservationTransport extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'reservations_transport';

    public const CREATED_AT = 'date_reservation';
    public const UPDATED_AT = 'date_modification';

    protected $fillable = [
        'reference',
        'tracking_number',
        'driver_token',
        'client_nom',
        'client_telephone',
        'client_email',
        'lieu_depart',
        'lieu_arrivee',
        'lat_depart',
        'lng_depart',
        'lat_arrivee',
        'lng_arrivee',
        'chauffeur_lat',
        'chauffeur_lng',
        'chauffeur_arrived',
        'chauffeur_arrived_at',
        'date_prise_en_charge',
        'nombre_personnes',
        'type_service',
        'notes_client',
        'statut',
        'prix_propose',
        'prix_accepte',
        'driver_id',
    ];

    protected $casts = [
        'lat_depart'          => 'float',
        'lng_depart'          => 'float',
        'lat_arrivee'         => 'float',
        'lng_arrivee'         => 'float',
        'chauffeur_lat'       => 'float',
        'chauffeur_lng'       => 'float',
        'chauffeur_arrived'   => 'boolean',
        'prix_accepte'        => 'boolean',
        'prix_propose'        => 'decimal:2',
        'date_prise_en_charge'=> 'datetime',
        'date_reservation'    => 'datetime',
        'date_modification'   => 'datetime',
        'chauffeur_arrived_at'=> 'datetime',
    ];

    /**
     * Relation avec le chauffeur assigné.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    /**
     * Relation avec les messages du chat.
     */
    public function conversations()
    {
        return $this->hasMany(TransportConversation::class, 'reservation_transport_id')->orderBy('created_at');
    }

    /**
     * Libellés des statuts en français.
     */
    public static function statutLabels(): array
    {
        return [
            'en_attente'          => 'En attente',
            'accepte'             => 'Accepté',
            'chauffeur_en_route'  => 'Chauffeur en route',
            'chauffeur_arrive'    => 'Chauffeur arrivé',
            'en_cours'            => 'Course en cours',
            'termine'             => 'Terminé',
            'annule'              => 'Annulé',
        ];
    }

    /**
     * Libellé du statut courant.
     */
    public function getStatutLabelAttribute(): string
    {
        return self::statutLabels()[$this->statut] ?? $this->statut;
    }

    /**
     * Libellés des types de service.
     */
    public static function typeServiceLabels(): array
    {
        return [
            'aeroport' => '✈️ Aéroport',
            'gare'     => '🚆 Gare',
            'evenement'=> '🎉 Événement',
            'course'   => '🚗 Course',
            'autre'    => '📍 Autre',
        ];
    }

    /**
     * Le chauffeur est-il actuellement trackable (position partageable) ?
     */
    public function isDriverTrackable(): bool
    {
        return in_array($this->statut, ['chauffeur_en_route', 'chauffeur_arrive', 'en_cours']);
    }
}
