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

class DefaultDataSeeder extends Seeder
{
    public function run(): void
    {
        echo "Seeding Users...\n";
        // Administrateur
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
        // Ports
        $ports = [
            ['nom' => 'Port Autonome de Lomé', 'code' => 'LOME', 'pays' => 'Togo', 'ville' => 'Lomé', 'type' => 'maritime', 'frais_base' => 500000, 'delai_moyen_jours' => 30],
            ['nom' => 'Port de Cotonou', 'code' => 'COTO', 'pays' => 'Bénin', 'ville' => 'Cotonou', 'type' => 'maritime', 'frais_base' => 550000, 'delai_moyen_jours' => 32],
            ['nom' => 'Port de Tema', 'code' => 'TEMA', 'pays' => 'Ghana', 'ville' => 'Tema', 'type' => 'maritime', 'frais_base' => 600000, 'delai_moyen_jours' => 28],
            ['nom' => 'Port d\'Abidjan', 'code' => 'ABID', 'pays' => 'Côte d\'Ivoire', 'ville' => 'Abidjan', 'type' => 'maritime', 'frais_base' => 650000, 'delai_moyen_jours' => 35],
            ['nom' => 'Livraison Burkina Faso', 'code' => 'OUAG', 'pays' => 'Burkina Faso', 'ville' => 'Ouagadougou', 'type' => 'terrestre', 'frais_base' => 800000, 'delai_moyen_jours' => 45],
        ];

        foreach ($ports as $port) {
            Port::updateOrCreate(['code' => $port['code']], $port);
        }

        echo "Seeding Params...\n";
        // Paramètres système
        $params = [
            ['cle' => 'taux_tva', 'valeur' => '18', 'type' => 'number', 'description' => 'Taux de TVA en pourcentage'],
            ['cle' => 'devise', 'valeur' => 'XOF', 'type' => 'string', 'description' => 'Devise principale (Franc CFA)'],
            ['cle' => 'email_contact', 'valeur' => 'contact@autoimport.com', 'type' => 'string', 'description' => 'Email de contact'],
            ['cle' => 'telephone_support', 'valeur' => '+22890000000', 'type' => 'string', 'description' => 'Téléphone support'],
        ];

        foreach ($params as $param) {
            ParametreSysteme::updateOrCreate(['cle' => $param['cle']], $param);
        }

        echo "Seeding Voitures...\n";
        // ...

        // Voitures
        $voitures = [
            [
                'marque' => 'Toyota',
                'modele' => 'Camry',
                'annee' => 2022,
                'kilometrage' => 15000,
                'prix' => 15000000,
                'pays_origine' => 'Japon',
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
                'etat' => 'bon',
                'carburant' => 'diesel',
                'transmission' => 'automatique',
                'type_vehicule' => 'suv',
                'disponibilite' => 'disponible',
            ],
        ];

        foreach ($voitures as $voiture) {
            Voiture::create($voiture);
        }

        // Voitures Location
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

        foreach ($locations as $loc) {
            VoitureLocation::create($loc);
        }

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
            ],
            [
                'nom' => 'Plaquettes de frein Mercedes',
                'reference' => 'MER-BRAK-002',
                'marque_compatible' => 'Mercedes-Benz',
                'categorie' => 'freinage',
                'prix' => 45000,
                'stock' => 20,
            ],
        ];

        foreach ($pieces as $piece) {
            PieceDetachee::create($piece);
        }
        // Sample Orders
        $v1 = Voiture::first();
        $p1 = Port::first();
        
        if ($v1 && $p1) {
            \App\Models\CommandeVoiture::create([
                'user_id' => 1,
                'voiture_id' => $v1->id,
                'port_destination_id' => $p1->id,
                'prix_voiture' => $v1->prix,
                'frais_port' => $p1->frais_base,
                'frais_douane' => $v1->prix * 0.1,
                'montant_total' => $v1->prix * 1.1 + $p1->frais_base,
                'reste_a_payer' => 0,
                'statut' => 'en_transit',
                'reference' => 'CV-ORDER-001',
            ]);
        }

        // Sample Revisions
        \App\Models\Revision::create([
            'user_id' => 1,
            'marque_vehicule' => 'Toyota',
            'modele_vehicule' => 'RAV4',
            'annee_vehicule' => 2019,
            'probleme_description' => 'Bruit suspect au niveau du train avant et vidange complète.',
            'type_revision' => 'complete',
            'statut' => 'en_attente',
            'reference' => 'REV-SAMPLE-001',
        ]);
    }
}
