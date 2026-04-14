<?php

namespace Database\Seeders;

use App\Models\PieceDetachee;
use Illuminate\Database\Seeder;

class PieceSeeder extends Seeder
{
    public function run()
    {
        $pieces = [
            [
                'nom' => 'Plaquettes de Frein Sport VMAX',
                'reference' => 'BK-9988',
                'marque_compatible' => 'Mercedes',
                'modele_compatible' => 'Classe C / Classe E',
                'categorie' => 'freinage',
                'prix' => 45000,
                'stock' => 12,
                'image' => 'piece_frein.png',
                'description' => 'Plaquettes de frein haute performance pour une sécurité maximale en conditions extrêmes.',
                'disponible' => true,
            ],
            [
                'nom' => 'Filtre à Huile Performance O1',
                'reference' => 'FL-102',
                'marque_compatible' => 'Toyota',
                'modele_compatible' => 'Camry / RAV4',
                'categorie' => 'filtration',
                'prix' => 12500,
                'stock' => 50,
                'image' => 'piece_filtre.png',
                'description' => 'Filtre haute capacité pour une pureté d\'huile optimale.',
                'disponible' => true,
            ],
            [
                'nom' => 'Amortisseur Gaz-Nitro Pro',
                'reference' => 'SUS-500',
                'marque_compatible' => 'Lexus',
                'modele_compatible' => 'RX 350',
                'categorie' => 'suspension',
                'prix' => 85000,
                'stock' => 4,
                'image' => 'piece_amortisseur.png',
                'description' => 'Confort de conduite inégalé avec la technologie Gaz-Nitro.',
                'disponible' => true,
            ],
            [
                'nom' => 'Bougie d\'Allumage Iridium X',
                'reference' => 'EL-443',
                'marque_compatible' => 'Universal',
                'modele_compatible' => 'Multi-modèles',
                'categorie' => 'electricite',
                'prix' => 8500,
                'stock' => 100,
                'image' => 'piece_bougie.png',
                'description' => 'Combustion parfaite et démarrage instantané.',
                'disponible' => true,
            ],
            [
                'nom' => 'Courroie de Distribution Renforcée',
                'reference' => 'BEL-009',
                'marque_compatible' => 'BMW',
                'modele_compatible' => 'Série 3 / Série 5',
                'categorie' => 'moteur',
                'prix' => 35000,
                'stock' => 8,
                'image' => 'piece_courroie.png',
                'description' => 'Durabilité extrême pour protéger votre moteur.',
                'disponible' => true,
            ],
            [
                'nom' => 'Optique Avant Full LED Matrix',
                'reference' => 'LIGHT-01',
                'marque_compatible' => 'Audi',
                'modele_compatible' => 'A4 / A6',
                'categorie' => 'optique_eclairage',
                'prix' => 250000,
                'stock' => 2,
                'image' => 'piece_optique.png',
                'description' => 'Éclairage intelligent pour une vision nocturne cristalline.',
                'disponible' => true,
            ],
        ];

        foreach ($pieces as $piece) {
            PieceDetachee::create($piece);
        }
    }
}
