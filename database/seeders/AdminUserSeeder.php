<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifie si l'admin existe déjà pour éviter les doublons
        if (!User::where('email', 'admin@auto.com')->exists()) {
            User::create([
                'nom' => 'Admin',
                'prenom' => 'Super',
                'email' => 'admin@auto.com',
                'telephone' => '+000000000000', // Valeur fictive pour l'admin
                'mot_de_passe' => Hash::make('password'), // Mot de passe par défaut
                'role' => 'admin',
                'is_admin' => true,
                'email_verified_at' => now(),
            ]);

            $this->command->info('Compte Admin créé : admin@auto.com / password');
        } else {
            $this->command->info('Le compte Admin existe déjà.');
        }
    }
}
