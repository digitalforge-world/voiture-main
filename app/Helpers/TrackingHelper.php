<?php

namespace App\Helpers;

class TrackingHelper
{
    /**
     * Génère un numéro de tracking unique
     * Format: XXX-YYYY-ZZZZ
     * XXX = Prefixe du type de service
     * YYYY = Année
     * ZZZZ = Numéro aléatoire
     */
    public static function generate(string $prefix): string
    {
        $year = date('Y');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));

        return "{$prefix}-{$year}-{$random}";
    }

    /**
     * Préfixes pour différents types de services
     */
    public static function prefixes(): array
    {
        return [
            'voiture' => 'CAR',      // Commande voiture
            'location' => 'LOC',     // Location
            'piece' => 'PCE',        // Pièce détachée
            'revision' => 'REV',     // Révision
            'tracking' => 'TRK',     // Tracking générique
        ];
    }

    /**
     * Génère un tracking pour une commande voiture
     */
    public static function forCar(): string
    {
        return self::generate(self::prefixes()['voiture']);
    }

    /**
     * Génère un tracking pour une location
     */
    public static function forRental(): string
    {
        return self::generate(self::prefixes()['location']);
    }

    /**
     * Génère un tracking pour une pièce
     */
    public static function forPart(): string
    {
        return self::generate(self::prefixes()['piece']);
    }

    /**
     * Génère un tracking pour une révision
     */
    public static function forRevision(): string
    {
        return self::generate(self::prefixes()['revision']);
    }

    /**
     * Vérifie si un numéro de tracking est valide
     */
    public static function isValid(string $tracking): bool
    {
        // Format: AAA-YYYY-XXXX ou AAA-YYYYMMDD-XXXX (plus flexible)
        $pattern = '/^[A-Z]{3}-[\d]+-[A-Z0-9]+$/';
        return preg_match($pattern, $tracking) === 1;
    }

    /**
     * Extrait le type de service depuis le tracking
     */
    public static function getType(string $tracking): ?string
    {
        if (!self::isValid($tracking)) {
            return null;
        }

        $prefix = substr($tracking, 0, 3);
        $prefixes = array_flip(self::prefixes());

        $type = $prefixes[$prefix] ?? null;
        
        // TRK est traité comme une voiture par défaut ou une recherche globale
        if ($prefix === 'TRK') return 'voiture';

        return $type;
    }
}
