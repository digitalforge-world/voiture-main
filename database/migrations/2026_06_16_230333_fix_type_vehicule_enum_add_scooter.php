<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // SQLite ne supporte pas ALTER COLUMN.
            // On récupère le CREATE TABLE existant, on modifie le CHECK,
            // on recrée la table puis on y copie les données.

            $result = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='voitures'");

            if (empty($result)) {
                return; // table absente, rien à faire
            }

            $createSql = $result[0]->sql;

            // Remplace l'ancienne liste par la nouvelle (avec 'scooter')
            // Laravel génère le CHECK avec des guillemets doubles
            $patterns = [
                // format avec guillemets doubles
                "\"type_vehicule\" in ('berline', 'suv', '4x4', 'pickup', 'utilitaire', 'coupe', 'break')",
                // format sans guillemets (selon la version)
                "type_vehicule in ('berline', 'suv', '4x4', 'pickup', 'utilitaire', 'coupe', 'break')",
            ];

            $replacement_dq = "\"type_vehicule\" in ('berline', 'suv', '4x4', 'pickup', 'utilitaire', 'coupe', 'break', 'scooter')";
            $replacement_nq = "type_vehicule in ('berline', 'suv', '4x4', 'pickup', 'utilitaire', 'coupe', 'break', 'scooter')";

            $newSql = str_replace($patterns[0], $replacement_dq, $createSql);
            if ($newSql === $createSql) {
                // Essai sans guillemets
                $newSql = str_replace($patterns[1], $replacement_nq, $createSql);
            }

            if ($newSql === $createSql) {
                // Le CHECK n'a pas été trouvé : on supprime simplement toute la contrainte CHECK
                // pour éviter le blocage (SQLite accepte n'importe quelle string de toute façon)
                $newSql = preg_replace(
                    "/,\s*check\s*\([^)]*type_vehicule[^)]*\)/i",
                    '',
                    $newSql
                );
            }

            // Crée une table temporaire avec le nouveau schéma
            $tmpSql = str_replace(
                '"voitures"',
                '"voitures_new"',
                $newSql
            );

            DB::statement('PRAGMA foreign_keys = OFF');
            DB::statement($tmpSql);
            DB::statement('INSERT INTO "voitures_new" SELECT * FROM "voitures"');
            DB::statement('DROP TABLE "voitures"');
            DB::statement('ALTER TABLE "voitures_new" RENAME TO "voitures"');
            DB::statement('PRAGMA foreign_keys = ON');

        } elseif ($driver === 'mysql') {
            DB::statement("ALTER TABLE voitures MODIFY COLUMN type_vehicule ENUM(
                'berline','suv','4x4','pickup','utilitaire','coupe','break','scooter'
            ) DEFAULT 'berline'");
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $result = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='voitures'");
            if (empty($result)) return;

            $createSql = $result[0]->sql;
            $newSql = str_replace(", 'scooter'", '', $createSql);
            $tmpSql  = str_replace('"voitures"', '"voitures_new"', $newSql);

            DB::statement('PRAGMA foreign_keys = OFF');
            DB::statement($tmpSql);
            DB::statement('INSERT INTO "voitures_new" SELECT * FROM "voitures"');
            DB::statement('DROP TABLE "voitures"');
            DB::statement('ALTER TABLE "voitures_new" RENAME TO "voitures"');
            DB::statement('PRAGMA foreign_keys = ON');

        } elseif ($driver === 'mysql') {
            DB::statement("ALTER TABLE voitures MODIFY COLUMN type_vehicule ENUM(
                'berline','suv','4x4','pickup','utilitaire','coupe','break'
            ) DEFAULT 'berline'");
        }
    }
};
