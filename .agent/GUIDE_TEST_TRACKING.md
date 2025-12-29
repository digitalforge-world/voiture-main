# 🔧 Guide de Test - Système de Tracking des Révisions

**Date:** 2025-12-28  
**Status:** ✅ Migration effectuée

---

## ✅ CE QUI A ÉTÉ CORRIGÉ

### Migration exécutée

```bash
php artisan migrate
```

**Colonnes ajoutées à `revisions`:**

-   ✅ `diagnostic_technique` (text, nullable)
-   ✅ `notes_internes` (text, nullable)
-   ✅ `client_nom` (string, nullable)
-   ✅ `client_email` (string, nullable)
-   ✅ `client_telephone` (string, nullable)

---

## 🧪 COMMENT TESTER

### 1. Créer une nouvelle demande de révision

#### Option A: Via le formulaire Web

```
1. Allez sur: http://votre-site.com/revisions
2. Remplissez le formulaire:
   - Marque: Toyota
   - Modèle: Corolla
   - Année: 2020
   - Immatriculation: AB-123-CD
   - Kilométrage: 45000
   - Problème: Bruit au niveau du moteur
   - Type: Complete
   - Nom: Jean Dupont
   - Email: jean@example.com
   - Téléphone: +237 690 00 00 00
3. Cliquez "Soumettre"
4. Vous devriez voir une page avec:
   "Votre numéro de tracking: REV-2024-XXXX"
```

#### Option B: Via Tinker (pour test rapide)

```bash
php artisan tinker
```

Puis exécutez:

```php
$tracking = \App\Helpers\TrackingHelper::forRevision();

\App\Models\Revision::create([
    'tracking_number' => $tracking,
    'reference' => 'REV-' . strtoupper(Str::random(8)),
    'marque_vehicule' => 'Toyota',
    'modele_vehicule' => 'Corolla',
    'annee_vehicule' => 2020,
    'immatriculation' => 'AB-123-CD',
    'kilometrage' => 45000,
    'probleme_description' => 'Bruit anormal au niveau du moteur lors de l\'accélération',
    'type_revision' => 'complete',
    'statut' => 'en_attente',
    'client_nom' => 'Jean Dupont',
    'client_email' => 'jean@example.com',
    'client_telephone' => '+237 690 00 00 00'
]);

echo "Numéro de tracking: $tracking\n";
```

### 2. Tester le suivi

```
1. Notez le numéro de tracking (ex: REV-2024-A3B7)
2. Allez sur: http://votre-site.com/tracking
3. Entrez le numéro: REV-2024-A3B7
4. Cliquez "Rechercher ma Commande"
5. Vous devriez voir les détails complets
```

---

## 🔍 VÉRIFICATIONS

### Vérifier que le tracking_number existe en DB

```bash
php artisan tinker
```

```php
// Vérifier le dernier enregistrement
$revision = \App\Models\Revision::latest()->first();
echo "Tracking: " . $revision->tracking_number . "\n";
echo "Reference: " . $revision->reference . "\n";
echo "Client: " . $revision->client_nom . "\n";
echo "Statut: " . $revision->statut . "\n";
```

### Vérifier qu'une révision peut être trouvée

```php
$tracking = 'REV-2024-XXXX'; // Remplacez par votre numéro
$revision = \App\Models\Revision::where('tracking_number', $tracking)->first();

if ($revision) {
    echo "✅ Révision trouvée!\n";
    echo "Client: " . $revision->client_nom . "\n";
    echo "Véhicule: " . $revision->marque_vehicule . " " . $revision->modele_vehicule . "\n";
} else {
    echo "❌ Révision non trouvée\n";
}
```

---

## 📝 TESTER LE FLUX ADMIN

### 1. Aller sur l'admin

```
URL: http://votre-site.com/admin/revisions
```

### 2. Voir la révision dans le tableau

-   Devrait afficher: Client, Véhicule, Problème, Diagnostic, Prix, Statut

### 3. Cliquer sur "Valider"

-   Modal s'ouvre avec toutes les sections

### 4. Remplir le modal

```
Diagnostic technique: "Courroie de distribution usée, nécessite remplacement urgent"
Interventions: "Remplacement courroie, vidange moteur"
Pièces: "Courroie distribution, filtre à huile, huile moteur 5W30"
Montant devis: 150000
Statut: Devis envoyé
☑️ Notifier le Client
```

### 5. Valider

-   Cliquer "Valider & Communiquer au Client"
-   Vérifier le message de succès

### 6. Vérifier en DB

```php
$revision = \App\Models\Revision::latest()->first();
echo "Diagnostic: " . $revision->diagnostic_technique . "\n";
echo "Montant: " . $revision->montant_devis . " FCFA\n";
echo "Date devis: " . $revision->date_devis . "\n";
```

---

## 🔄 TESTER LE TRACKING CÔTÉ CLIENT

### 1. Retourner sur /tracking

```
URL: http://votre-site.com/tracking
```

### 2. Entrer le numéro

```
Tracking: REV-2024-XXXX
[Rechercher]
```

### 3. Vérifier l'affichage

Vous devriez voir:

```
┌─────────────────────────────────────┐
│ REV-2024-XXXX  [DEVIS ENVOYÉ]     │
│ Type: RÉVISION MÉCANIQUE           │
└─────────────────────────────────────┘

Barre de progression:
[✓ Reçu] → [✓ Validé] → [En cours] → [Terminé]

┌─────────────────────────┐
│ 🚗 VÉHICULE            │
│ Toyota Corolla         │
│ 2020 • AB-123-CD       │
│ 45,000 km              │
└─────────────────────────┘

┌─────────────────────────┐
│ 💰 DEVIS ESTIMATIF     │
│   150,000 FCFA         │
└─────────────────────────┘

┌─────────────────────────┐
│ ⚠️  PROBLÈME SIGNALÉ   │
│ Bruit anormal...       │
└─────────────────────────┘

┌─────────────────────────┐
│ ✅ DIAGNOSTIC          │
│ Courroie usée...       │
└─────────────────────────┘

┌──────────────┬──────────┐
│ INTERVENTIONS│ PIÈCES   │
│ Remplacement │ Courroie │
└──────────────┴──────────┘

┌─────────────────────────┐
│ 📅 HISTORIQUE          │
│ • Demande: 28/12 10:00 │
│ • Diagnostic: 28/12... │
│ • Devis: 28/12...      │
└─────────────────────────┘
```

---

## ⚠️ PROBLÈMES POSSIBLES

### "Aucune commande trouvée"

**Causes possibles:**

1. Le tracking_number n'est pas dans la DB
2. Format incorrect du numéro
3. Problème avec TrackingHelper

**Solution:**

```php
// Vérifier en DB
\App\Models\Revision::where('tracking_number', 'LIKE', 'REV%')->get(['id', 'tracking_number', 'client_nom']);
```

### "Format de numéro de tracking invalide"

**Cause:** Le format ne correspond pas au pattern `XXX-YYYY-ZZZZ`

**Solution:** Vérifier que le numéro est bien généré:

```php
$tracking = \App\Helpers\TrackingHelper::forRevision();
echo $tracking; // Devrait être: REV-2024-XXXX
```

### Modal ne s'ouvre pas

**Cause:** JavaScript Lucide icons non chargé

**Solution:** Vérifier que Lucide est inclus dans `layouts/admin.blade.php`:

```html
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
```

---

## 📊 COMMANDES UTILES

### Voir toutes les révisions

```sql
SELECT id, tracking_number, client_nom, statut, montant_devis, date_demande
FROM revisions
ORDER BY id DESC
LIMIT 10;
```

### Compter les révisions par statut

```php
\App\Models\Revision::groupBy('statut')->selectRaw('statut, count(*) as total')->get();
```

### Nettoyer les révisions de test

```php
\App\Models\Revision::where('client_nom', 'LIKE', '%test%')->delete();
```

---

## ✅ CHECKLIST DE TEST

-   [ ] Migration exécutée avec succès
-   [ ] Peut créer une nouvelle révision via /revisions
-   [ ] tracking_number est généré (format REV-2024-XXXX)
-   [ ] Redirection vers /tracking/success avec le numéro
-   [ ] Peut entrer le numéro sur /tracking
-   [ ] Voit les détails complets sur /tracking/show
-   [ ] Admin voit la révision dans /admin/revisions
-   [ ] Modal de validation s'ouvre
-   [ ] Peut remplir diagnostic + prix
-   [ ] Sauvegarde réussit
-   [ ] Client voit les mises à jour sur /tracking
-   [ ] Timeline affiche les dates correctement
-   [ ] Prix du devis affiché en gros

---

## 🎯 PROCHAINES ÉTAPES

Si tout fonctionne:

1. ✅ Tester avec plusieurs révisions
2. ✅ Tester différents statuts
3. ✅ Vérifier responsive design mobile
4. 🔄 Implémenter notifications email/SMS
5. 📸 Ajouter upload photos
6. 💬 Ajouter système de chat

---

**Bon test ! 🚀**

Si quelque chose ne fonctionne pas, vérifiez:

1. Les logs Laravel: `storage/logs/laravel.log`
2. La console navigateur (F12)
3. Les données en DB avec Tinker
