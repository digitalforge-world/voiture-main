<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        $statements = [
            // Triggers
            "CREATE TRIGGER before_insert_commande_voiture
            BEFORE INSERT ON commandes_voitures
            FOR EACH ROW
            BEGIN
                IF NEW.reference IS NULL OR NEW.reference = '' THEN
                    SET NEW.reference = CONCAT('CV-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
                END IF;
                SET NEW.reste_a_payer = NEW.montant_total - NEW.acompte_verse;
                IF NEW.tracking_number IS NULL OR NEW.tracking_number = '' THEN
                    SET NEW.tracking_number = CONCAT('TRK-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
                END IF;
            END",

            "CREATE TRIGGER before_insert_location
            BEFORE INSERT ON locations
            FOR EACH ROW
            BEGIN
                IF NEW.reference IS NULL OR NEW.reference = '' THEN
                    SET NEW.reference = CONCAT('LOC-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
                END IF;
                IF NEW.tracking_number IS NULL OR NEW.tracking_number = '' THEN
                    SET NEW.tracking_number = CONCAT('TRK-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
                END IF;
            END",

            "CREATE TRIGGER before_insert_commande_piece
            BEFORE INSERT ON commandes_pieces
            FOR EACH ROW
            BEGIN
                IF NEW.reference IS NULL OR NEW.reference = '' THEN
                    SET NEW.reference = CONCAT('CP-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
                END IF;
                IF NEW.tracking_number IS NULL OR NEW.tracking_number = '' THEN
                    SET NEW.tracking_number = CONCAT('TRK-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
                END IF;
            END",

            "CREATE TRIGGER before_insert_revision
            BEFORE INSERT ON revisions
            FOR EACH ROW
            BEGIN
                IF NEW.reference IS NULL OR NEW.reference = '' THEN
                    SET NEW.reference = CONCAT('REV-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
                END IF;
                IF NEW.tracking_number IS NULL OR NEW.tracking_number = '' THEN
                    SET NEW.tracking_number = CONCAT('TRK-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
                END IF;
            END",

            "CREATE TRIGGER before_insert_echange_piece
            BEFORE INSERT ON echanges_pieces
            FOR EACH ROW
            BEGIN
                IF NEW.reference IS NULL OR NEW.reference = '' THEN
                    SET NEW.reference = CONCAT('ECH-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
                END IF;
                IF NEW.tracking_number IS NULL OR NEW.tracking_number = '' THEN
                    SET NEW.tracking_number = CONCAT('TRK-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
                END IF;
            END",

            "CREATE TRIGGER before_insert_paiement
            BEFORE INSERT ON paiements
            FOR EACH ROW
            BEGIN
                IF NEW.reference IS NULL OR NEW.reference = '' THEN
                    SET NEW.reference = CONCAT('PAY-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '-', LPAD(FLOOR(RAND() * 1000), 3, '0'));
                END IF;
                IF NEW.tracking_number IS NULL OR NEW.tracking_number = '' THEN
                    SET NEW.tracking_number = CONCAT('TRK-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
                END IF;
            END",

            "CREATE TRIGGER after_insert_ligne_commande_piece
            AFTER INSERT ON lignes_commandes_pieces
            FOR EACH ROW
            BEGIN
                UPDATE pieces_detachees 
                SET stock = stock - NEW.quantite 
                WHERE id = NEW.piece_id;
            END",

            "CREATE TRIGGER after_update_commande_piece_annulee
            AFTER UPDATE ON commandes_pieces
            FOR EACH ROW
            BEGIN
                IF NEW.statut = 'annule' AND OLD.statut != 'annule' THEN
                    UPDATE pieces_detachees p
                    INNER JOIN lignes_commandes_pieces lcp ON p.id = lcp.piece_id
                    SET p.stock = p.stock + lcp.quantite
                    WHERE lcp.commande_piece_id = NEW.id;
                END IF;
            END",

            "CREATE TRIGGER after_insert_commande_voiture_update_dispo
            AFTER INSERT ON commandes_voitures
            FOR EACH ROW
            BEGIN
                UPDATE voitures 
                SET disponibilite = 'reserve' 
                WHERE id = NEW.voiture_id AND disponibilite = 'disponible';
            END",

            // Procedures
            "CREATE PROCEDURE sp_rechercher_pieces_compatibles(
                IN p_marque VARCHAR(100),
                IN p_modele VARCHAR(150),
                IN p_annee YEAR,
                IN p_moteur VARCHAR(50)
            )
            BEGIN
                SELECT 
                    p.*,
                    CASE 
                        WHEN p.marque_compatible = p_marque 
                            AND (p.modele_compatible IS NULL OR p.modele_compatible LIKE CONCAT('%', p_modele, '%'))
                            AND (p.annee_debut IS NULL OR p.annee_debut <= p_annee)
                            AND (p.annee_fin IS NULL OR p.annee_fin >= p_annee)
                            AND (p.moteur_compatible IS NULL OR p.moteur_compatible LIKE CONCAT('%', p_moteur, '%'))
                        THEN 'compatible'
                        WHEN p.marque_compatible = p_marque THEN 'verification_recommandee'
                        ELSE 'non_compatible'
                    END as niveau_compatibilite
                FROM pieces_detachees p
                WHERE p.disponible = TRUE
                    AND p.stock > 0
                    AND (
                        p.marque_compatible = p_marque
                        OR p.compatible_avec LIKE CONCAT('%', p_marque, '%')
                    )
                ORDER BY niveau_compatibilite ASC, p.prix ASC;
            END",

            "CREATE PROCEDURE sp_calculer_frais_import(
                IN p_voiture_id BIGINT,
                IN p_port_id BIGINT,
                OUT p_frais_total DECIMAL(12,2)
            )
            BEGIN
                DECLARE v_prix_voiture DECIMAL(12,2);
                DECLARE v_frais_port DECIMAL(10,2);
                
                SELECT prix INTO v_prix_voiture FROM voitures WHERE id = p_voiture_id;
                SELECT frais_base INTO v_frais_port FROM ports WHERE id = p_port_id;
                
                SET p_frais_total = v_frais_port + (v_prix_voiture * 0.10);
            END",

            "CREATE PROCEDURE sp_stats_mensuelles(IN p_mois INT, IN p_annee INT)
            BEGIN
                SELECT 
                    'Commandes Voitures' as type,
                    COUNT(*) as nombre,
                    SUM(montant_total) as montant_total
                FROM commandes_voitures
                WHERE MONTH(date_commande) = p_mois AND YEAR(date_commande) = p_annee
                
                UNION ALL
                
                SELECT 
                    'Locations' as type,
                    COUNT(*) as nombre,
                    SUM(montant_total) as montant_total
                FROM locations
                WHERE MONTH(date_reservation) = p_mois AND YEAR(date_reservation) = p_annee
                
                UNION ALL
                
                SELECT 
                    'Pièces Détachées' as type,
                    COUNT(*) as nombre,
                    SUM(montant_total) as montant_total
                FROM commandes_pieces
                WHERE MONTH(date_commande) = p_mois AND YEAR(date_commande) = p_annee;
            END",

            "CREATE PROCEDURE sp_rechercher_par_tracking(IN p_tracking VARCHAR(40))
            BEGIN
                SELECT 'commande_voiture' AS service_type, id, reference, tracking_number, statut, montant_total AS montant, date_commande AS date_action
                FROM commandes_voitures WHERE tracking_number = p_tracking

                UNION ALL

                SELECT 'location' AS service_type, id, reference, tracking_number, statut, montant_total AS montant, date_reservation AS date_action
                FROM locations WHERE tracking_number = p_tracking

                UNION ALL

                SELECT 'commande_piece' AS service_type, id, reference, tracking_number, statut, montant_total AS montant, date_commande AS date_action
                FROM commandes_pieces WHERE tracking_number = p_tracking

                UNION ALL

                SELECT 'echange_piece' AS service_type, id, reference, tracking_number, statut, NULL AS montant, date_demande AS date_action
                FROM echanges_pieces WHERE tracking_number = p_tracking

                UNION ALL

                SELECT 'revision' AS service_type, id, reference, tracking_number, statut, montant_devis AS montant, date_demande AS date_action
                FROM revisions WHERE tracking_number = p_tracking

                UNION ALL

                SELECT 'paiement' AS service_type, id, reference, tracking_number, statut, montant AS montant, date_paiement AS date_action
                FROM paiements WHERE tracking_number = p_tracking;
            END",
        ];

        foreach ($statements as $s) {
            DB::unprepared($s);
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        $triggers = [
            'before_insert_commande_voiture',
            'before_insert_location',
            'before_insert_commande_piece',
            'before_insert_revision',
            'before_insert_echange_piece',
            'before_insert_paiement',
            'after_insert_ligne_commande_piece',
            'after_update_commande_piece_annulee',
            'after_insert_commande_voiture_update_dispo',
        ];

        $procs = [
            'sp_rechercher_pieces_compatibles',
            'sp_calculer_frais_import',
            'sp_stats_mensuelles',
            'sp_rechercher_par_tracking',
        ];

        foreach ($triggers as $t) {
            DB::unprepared("DROP TRIGGER IF EXISTS {$t};");
        }

        foreach ($procs as $p) {
            DB::unprepared("DROP PROCEDURE IF EXISTS {$p};");
        }
    }
};
