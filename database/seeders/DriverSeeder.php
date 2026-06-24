<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Driver::where('identifiant', 'chauffeur1')->exists()) {
            Driver::create([
                'nom'                      => 'Dupont',
                'prenom'                   => 'Jean',
                'telephone'                => '+225 07 01 02 03 04',
                'identifiant'              => 'chauffeur1',
                'mot_de_passe'             => 'password', // Mutator will automatically hash this
                'vehicule_marque'          => 'Toyota',
                'vehicule_modele'          => 'Prado',
                'vehicule_immatriculation' => '1234-AB-01',
                'vehicule_couleur'         => 'Noir métallisé',
                'statut'                   => 'actif',
            ]);

            $this->command->info('Chauffeur de test créé : chauffeur1 / password');
        }
    }
}
