<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Port;
use App\Models\Voiture;
use App\Models\PieceDetachee;
use App\Models\VoitureLocation;
use App\Models\ParametreSysteme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DefaultDataSeeder extends Seeder
{
    public function run(): void
    {
        echo "Seeding Users...\n";
        // Administrateur par défaut
        User::updateOrCreate(
            ['email' => 'admin@autoimport.com'],
            [
                'nom' => 'Admin',
                'prenom' => 'Système',
                'telephone' => '+22890000000',
                'mot_de_passe' => Hash::make('Admin@2025'),
                'role' => 'admin',
                'actif' => true,
            ]
        );

        echo "Seeding Ports...\n";
        // Ports de destination
        $ports = [
            ['nom' => 'Port Autonome de Lomé', 'code' => 'LOME', 'pays' => 'Togo', 'ville' => 'Lomé', 'type' => 'maritime', 'frais_base' => 500000, 'delai_moyen_jours' => 30, 'actif' => true],
            ['nom' => 'Port de Cotonou', 'code' => 'COTO', 'pays' => 'Bénin', 'ville' => 'Cotonou', 'type' => 'maritime', 'frais_base' => 550000, 'delai_moyen_jours' => 32, 'actif' => true],
            ['nom' => 'Port de Tema', 'code' => 'TEMA', 'pays' => 'Ghana', 'ville' => 'Tema', 'type' => 'maritime', 'frais_base' => 600000, 'delai_moyen_jours' => 28, 'actif' => true],
            ['nom' => 'Port d\'Abidjan', 'code' => 'ABID', 'pays' => 'Côte d\'Ivoire', 'ville' => 'Abidjan', 'type' => 'maritime', 'frais_base' => 650000, 'delai_moyen_jours' => 35, 'actif' => true],
            ['nom' => 'Livraison Ouagadougou', 'code' => 'OUAG', 'pays' => 'Burkina Faso', 'ville' => 'Ouagadougou', 'type' => 'terrestre', 'frais_base' => 800000, 'delai_moyen_jours' => 45, 'actif' => true],
        ];

        foreach ($ports as $port) {
            Port::updateOrCreate(['code' => $port['code']], $port);
        }

        echo "Seeding Config Settings...\n";
        // Paramètres système globaux
        $settings = [
            'site_name' => 'AutoImport Hub',
            'site_description' => 'Plateforme complète pour l\'importation, la location et l\'entretien automobile en Afrique de l\'Ouest.',
            'site_display_mode' => 'both',
            'contact_email' => 'contact@auto.com',
            'contact_phone' => '+228 90 00 00 00',
            'contact_address' => 'Lomé, Togo',
            'marques_disponibles' => 'Toyota, Mercedes-Benz, BMW, Hyundai, Volkswagen, Peugeot, Audi, Lexus, Honda, Kia',
            'pays_disponibles' => 'Japon, Allemagne, Corée du Sud, France, Etats-Unis, Chine',
            'devise' => 'FCFA',
            'taux_tva' => '18'
        ];

        foreach ($settings as $cle => $valeur) {
            ParametreSysteme::updateOrCreate(['cle' => $cle], ['valeur' => $valeur]);
        }

        echo "Seeding Voitures...\n";
        // Véhicules à l'importation
        $voitures = [
            [
                'marque' => 'Toyota',
                'modele' => 'Camry',
                'annee' => 2022,
                'kilometrage' => 15000,
                'prix' => 15000000,
                'pays_origine' => 'Japon',
                'ville_origine' => 'Osaka',
                'etat' => 'excellent',
                'carburant' => 'essence',
                'transmission' => 'automatique',
                'type_vehicule' => 'berline',
                'disponibilite' => 'disponible',
            ],
            [
                'marque' => 'Mercedes-Benz',
                'modele' => 'G-Class',
                'annee' => 2023,
                'kilometrage' => 5000,
                'prix' => 85000000,
                'pays_origine' => 'Allemagne',
                'ville_origine' => 'Stuttgart',
                'etat' => 'neuf',
                'carburant' => 'essence',
                'transmission' => 'automatique',
                'type_vehicule' => '4x4',
                'disponibilite' => 'disponible',
            ],
            [
                'marque' => 'Hyundai',
                'modele' => 'Tucson',
                'annee' => 2021,
                'kilometrage' => 35000,
                'prix' => 12000000,
                'pays_origine' => 'Corée du Sud',
                'ville_origine' => 'Seoul',
                'etat' => 'bon',
                'carburant' => 'diesel',
                'transmission' => 'automatique',
                'type_vehicule' => 'suv',
                'disponibilite' => 'disponible',
            ],
        ];

        foreach ($voitures as $vData) {
            $voiture = Voiture::firstOrNew([
                'marque' => $vData['marque'],
                'modele' => $vData['modele'],
                'annee' => $vData['annee']
            ]);
            
            $voiture->fill($vData);
            
            // Force la génération d'un slug si celui-ci est vide
            if (empty($voiture->slug)) {
                $voiture->slug = Voiture::generateUniqueSlug($voiture);
            }
            
            $voiture->save();
        }

        echo "Seeding Rental Vehicles...\n";
        // Véhicules de location
        $locations = [
            [
                'marque' => 'Toyota',
                'modele' => 'Corolla',
                'annee' => 2020,
                'immatriculation' => 'TG-1234-AB',
                'prix_jour' => 25000,
                'caution' => 100000,
                'disponible' => true,
                'categorie' => 'economique',
            ],
            [
                'marque' => 'Range Rover',
                'modele' => 'Vogue',
                'annee' => 2022,
                'immatriculation' => 'TG-5678-CD',
                'prix_jour' => 150000,
                'caution' => 500000,
                'disponible' => true,
                'categorie' => 'premium',
            ],
        ];

        foreach ($locations as $locData) {
            VoitureLocation::updateOrCreate(['immatriculation' => $locData['immatriculation']], $locData);
        }

        echo "Seeding Spare Parts...\n";
        // Pièces détachées
        $pieces = [
            [
                'nom' => 'Filtre à huile Toyota',
                'reference' => 'TOY-FILT-001',
                'marque_compatible' => 'Toyota',
                'modele_compatible' => 'Camry, Corolla',
                'categorie' => 'moteur',
                'prix' => 5000,
                'stock' => 50,
                'disponible' => true,
            ],
            [
                'nom' => 'Plaquettes de frein Mercedes',
                'reference' => 'MER-BRAK-002',
                'marque_compatible' => 'Mercedes-Benz',
                'categorie' => 'freinage',
                'prix' => 45000,
                'stock' => 20,
                'disponible' => true,
            ],
        ];

        foreach ($pieces as $pData) {
            PieceDetachee::updateOrCreate(['reference' => $pData['reference']], $pData);
        }

        echo "Database Seeding Completed Successfully.\n";
    }
}
