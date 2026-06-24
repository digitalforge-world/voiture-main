<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Car3DSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cars = \App\Models\Voiture::all();
        foreach ($cars as $idx => $car) {
            // Assigne des modèles GLB de démonstration alternatifs
            if ($idx % 2 === 0) {
                $car->model_3d = 'https://threejs.org/examples/models/gltf/ferrari.glb';
            } else {
                $car->model_3d = 'https://modelviewer.dev/shared-assets/models/glTF-Sample-Assets/Models/ToyCar/glTF-Binary/ToyCar.glb';
            }
            $car->save();
        }
    }
}
