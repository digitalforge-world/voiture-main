<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations_transport', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // TRS-2026-XXXX
            $table->string('tracking_number')->unique(); // TRK-2026-XXXX (partagé avec le client)
            $table->string('driver_token')->unique(); // Token secret pour la page GPS chauffeur

            // Infos client (réservation anonyme, téléphone obligatoire)
            $table->string('client_nom');
            $table->string('client_telephone'); // OBLIGATOIRE
            $table->string('client_email')->nullable();

            // Points géographiques
            $table->string('lieu_depart');
            $table->string('lieu_arrivee');
            $table->decimal('lat_depart', 10, 7)->nullable();
            $table->decimal('lng_depart', 10, 7)->nullable();
            $table->decimal('lat_arrivee', 10, 7)->nullable();
            $table->decimal('lng_arrivee', 10, 7)->nullable();

            // Position GPS du chauffeur (mise à jour en temps réel)
            $table->decimal('chauffeur_lat', 10, 7)->nullable();
            $table->decimal('chauffeur_lng', 10, 7)->nullable();
            $table->boolean('chauffeur_arrived')->default(false); // Déclenche notification client
            $table->timestamp('chauffeur_arrived_at')->nullable();

            // Détails de la course
            $table->dateTime('date_prise_en_charge');
            $table->integer('nombre_personnes')->default(1);
            $table->enum('type_service', ['aeroport', 'gare', 'evenement', 'course', 'autre'])->default('course');
            $table->text('notes_client')->nullable();

            // Statut avec cycle de vie complet
            $table->enum('statut', [
                'en_attente',         // Vient d'être créé
                'accepte',            // Admin a accepté
                'chauffeur_en_route', // Chauffeur est parti vers le client
                'chauffeur_arrive',   // Chauffeur est arrivé au point de départ
                'en_cours',           // Course en cours
                'termine',            // Course terminée
                'annule',             // Annulé
            ])->default('en_attente');

            // Négociation de prix
            $table->decimal('prix_propose', 10, 2)->nullable(); // Proposé par admin
            $table->boolean('prix_accepte')->default(false);    // Accepté par client

            $table->timestamp('date_reservation')->useCurrent();
            $table->timestamp('date_modification')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations_transport');
    }
};
