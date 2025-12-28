-- ============================================
-- BASE DE DONNÉES AUTOIMPORT HUB
-- Optimisée pour MySQL 8.0+ et Laravel
-- ============================================

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS autoimport_hub 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE autoimport_hub;

-- TABLE: users
-- Gestion des users (clients, agents, admins)
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telephone VARCHAR(20) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('client', 'agent', 'admin') DEFAULT 'client',
    adresse TEXT,
    ville VARCHAR(100),
    pays VARCHAR(100) DEFAULT 'Togo',
    photo_profil VARCHAR(255),
    actif BOOLEAN DEFAULT TRUE,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_actif (actif)
) ENGINE=InnoDB;

-- ============================================
-- TABLE: ports
-- Ports de destination pour l'importation
-- ============================================
CREATE TABLE ports (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    code VARCHAR(10) UNIQUE NOT NULL,
    pays VARCHAR(100) NOT NULL,
    ville VARCHAR(100) NOT NULL,
    type ENUM('maritime', 'terrestre', 'mixte') DEFAULT 'maritime',
    frais_base DECIMAL(10,2) DEFAULT 0.00,
    delai_moyen_jours INT DEFAULT 30,
    description TEXT,
    actif BOOLEAN DEFAULT TRUE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_actif (actif),
    INDEX idx_pays (pays)
) ENGINE=InnoDB;

-- ============================================
-- TABLE: voitures
-- Catalogue des voitures à vendre/importer
-- ============================================
CREATE TABLE voitures (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(100) NOT NULL,
    annee YEAR NOT NULL,
    kilometrage INT UNSIGNED,
    prix DECIMAL(12,2) NOT NULL,
    pays_origine VARCHAR(50) NOT NULL,
    ville_origine VARCHAR(100),
    etat ENUM('neuf', 'occasion', 'excellent', 'bon', 'moyen') DEFAULT 'occasion',
    moteur VARCHAR(50),
    cylindree VARCHAR(20),
    puissance VARCHAR(20),
    carburant ENUM('essence', 'diesel', 'hybride', 'electrique', 'gpl') DEFAULT 'essence',
    transmission ENUM('manuelle', 'automatique', 'semi-automatique') DEFAULT 'manuelle',
    couleur VARCHAR(30),
    nombre_portes TINYINT,
    nombre_places TINYINT,
    disponibilite ENUM('disponible', 'reserve', 'vendu', 'en_transit') DEFAULT 'disponible',
    type_vehicule ENUM('berline', 'suv', '4x4', 'pickup', 'utilitaire', 'coupe', 'break') DEFAULT 'berline',
    description TEXT,
    options_equipements TEXT,
    numero_chassis VARCHAR(50),
    port_recommande_id BIGINT UNSIGNED,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_marque (marque),
    INDEX idx_disponibilite (disponibilite),
    INDEX idx_pays_origine (pays_origine),
    INDEX idx_prix (prix),
    INDEX idx_annee (annee),
    FOREIGN KEY (port_recommande_id) REFERENCES ports(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- TABLE: photos_voitures
-- Photos des voitures
-- ============================================
CREATE TABLE photos_voitures (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    voiture_id BIGINT UNSIGNED NOT NULL,
    url VARCHAR(255) NOT NULL,
    ordre TINYINT DEFAULT 1,
    principale BOOLEAN DEFAULT FALSE,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (voiture_id) REFERENCES voitures(id) ON DELETE CASCADE,
    INDEX idx_voiture (voiture_id),
    INDEX idx_principale (principale)
) ENGINE=InnoDB;

-- ============================================
-- TABLE: commandes_voitures
-- Commandes d'importation de voitures
-- ============================================
CREATE TABLE commandes_voitures (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reference VARCHAR(20) UNIQUE NOT NULL,
    tracking_number VARCHAR(40) DEFAULT NULL,
    user_id BIGINT UNSIGNED NULL,
    voiture_id BIGINT UNSIGNED NOT NULL,
    port_destination_id BIGINT UNSIGNED NOT NULL,
    prix_voiture DECIMAL(12,2) NOT NULL,
    frais_import DECIMAL(10,2) DEFAULT 0.00,
    frais_port DECIMAL(10,2) DEFAULT 0.00,
    frais_douane DECIMAL(10,2) DEFAULT 0.00,
    autres_frais DECIMAL(10,2) DEFAULT 0.00,
    montant_total DECIMAL(12,2) NOT NULL,
    acompte_verse DECIMAL(12,2) DEFAULT 0.00,
    reste_a_payer DECIMAL(12,2) NOT NULL,
    statut ENUM('en_attente', 'confirme', 'paiement_partiel', 'paye', 'en_transit', 'arrive', 'livre', 'annule') DEFAULT 'en_attente',
    date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_confirmation TIMESTAMP NULL,
    date_paiement_complet TIMESTAMP NULL,
    date_expedition TIMESTAMP NULL,
    date_livraison_estimee DATE,
    date_livraison_reelle DATE,
    notes TEXT,
    notes_admin TEXT,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (voiture_id) REFERENCES voitures(id) ON DELETE RESTRICT,
    FOREIGN KEY (port_destination_id) REFERENCES ports(id) ON DELETE RESTRICT,
    INDEX idx_reference (reference),
    UNIQUE KEY uq_commandes_voitures_tracking (tracking_number),
    INDEX idx_user (user_id),
    INDEX idx_statut (statut),
    INDEX idx_date_commande (date_commande)
) ENGINE=InnoDB;

-- ============================================
-- TABLE: voitures_location
-- Catalogue des voitures disponibles à la location
-- ============================================
CREATE TABLE voitures_location (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(100) NOT NULL,
    annee YEAR NOT NULL,
    immatriculation VARCHAR(30) UNIQUE NOT NULL,
    couleur VARCHAR(30),
    kilometrage INT UNSIGNED,
    transmission ENUM('manuelle', 'automatique') DEFAULT 'manuelle',
    carburant ENUM('essence', 'diesel') DEFAULT 'essence',
    nombre_places TINYINT,
    prix_jour DECIMAL(8,2) NOT NULL,
    caution DECIMAL(10,2) NOT NULL,
    disponible BOOLEAN DEFAULT TRUE,
    categorie ENUM('economique', 'confort', 'premium', 'suv', 'utilitaire') DEFAULT 'economique',
    description TEXT,
    equipements TEXT,
    photo_principale VARCHAR(255),
    etat_general ENUM('excellent', 'bon', 'moyen') DEFAULT 'bon',
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_disponible (disponible),
    INDEX idx_categorie (categorie),
    INDEX idx_prix (prix_jour)
) ENGINE=InnoDB;

-- ============================================
-- TABLE: locations
-- Réservations et locations de véhicules
-- ============================================
CREATE TABLE locations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    tracking_number VARCHAR(40) DEFAULT NULL,
    user_id BIGINT UNSIGNED NULL,
    voiture_location_id BIGINT UNSIGNED NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    date_debut_reelle TIMESTAMP NULL,
    date_fin_reelle TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    montant_location DECIMAL(10,2) NOT NULL,
    caution DECIMAL(10,2) NOT NULL,
    frais_supplementaires DECIMAL(10,2) DEFAULT 0.00,
    montant_total DECIMAL(10,2) NOT NULL,
    statut ENUM('reserve', 'confirme', 'en_cours', 'termine', 'annule') DEFAULT 'reserve',
    etat_depart TEXT,
    kilometrage_depart INT UNSIGNED,
    etat_retour TEXT,
    kilometrage_retour INT UNSIGNED,
    commentaires TEXT,
    date_reservation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (voiture_location_id) REFERENCES voitures_location(id) ON DELETE RESTRICT,
    INDEX idx_reference (reference),
    UNIQUE KEY uq_locations_tracking (tracking_number),
    INDEX idx_tracking_locations (tracking_number),
    INDEX idx_user (user_id),
    INDEX idx_statut (statut),
    INDEX idx_dates (date_debut, date_fin)
) ENGINE=InnoDB;

-- ============================================
-- TABLE: pieces_detachees
-- Catalogue des pièces détachées
-- ============================================
CREATE TABLE pieces_detachees (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    reference VARCHAR(50) UNIQUE NOT NULL,
    marque_compatible VARCHAR(100) NOT NULL,
    modele_compatible VARCHAR(150),
    annee_debut YEAR,
    annee_fin YEAR,
    moteur_compatible VARCHAR(50),
    numero_chassis_compatible VARCHAR(100),
    categorie ENUM('moteur', 'transmission', 'suspension', 'freinage', 'carrosserie', 'electricite', 'interieur', 'pneumatique', 'autre') NOT NULL,
    sous_categorie VARCHAR(100),
    prix DECIMAL(10,2) NOT NULL,
    stock INT UNSIGNED DEFAULT 0,
    stock_minimum INT UNSIGNED DEFAULT 5,
    etat ENUM('neuf', 'occasion', 'reconditionne') DEFAULT 'neuf',
    origine VARCHAR(50),
    description TEXT,
    specifications TEXT,
    compatible_avec TEXT,
    image VARCHAR(255),
    poids DECIMAL(6,2),
    dimensions VARCHAR(50),
    garantie_mois TINYINT DEFAULT 0,
    disponible BOOLEAN DEFAULT TRUE,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_reference (reference),
    INDEX idx_marque (marque_compatible),
    INDEX idx_categorie (categorie),
    INDEX idx_stock (stock),
    INDEX idx_disponible (disponible),
    FULLTEXT idx_search (nom, description, marque_compatible, modele_compatible)
) ENGINE=InnoDB;

-- ============================================
-- TABLE: commandes_pieces
-- Commandes de pièces détachées
-- ============================================
CREATE TABLE commandes_pieces (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reference VARCHAR(20) UNIQUE NOT NULL,
    tracking_number VARCHAR(40) DEFAULT NULL,
    user_id BIGINT UNSIGNED NULL,
    montant_total DECIMAL(10,2) NOT NULL,
    statut ENUM('en_attente', 'confirme', 'en_preparation', 'expedie', 'livre', 'annule') DEFAULT 'en_attente',
    type_livraison ENUM('retrait', 'livraison') DEFAULT 'retrait',
    adresse_livraison TEXT,
    frais_livraison DECIMAL(8,2) DEFAULT 0.00,
    date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_livraison_estimee DATE,
    date_livraison_reelle DATE,
    notes TEXT,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_reference (reference),
    UNIQUE KEY uq_commandes_pieces_tracking (tracking_number),
    INDEX idx_tracking_commandes_pieces (tracking_number),
    INDEX idx_user (user_id),
    INDEX idx_statut (statut),
    INDEX idx_date_commande (date_commande)
) ENGINE=InnoDB;

-- ============================================
-- TABLE: lignes_commandes_pieces
-- Détails des commandes de pièces
-- ============================================
CREATE TABLE lignes_commandes_pieces (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    commande_piece_id BIGINT UNSIGNED NOT NULL,
    piece_id BIGINT UNSIGNED NOT NULL,
    quantite INT UNSIGNED NOT NULL DEFAULT 1,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    montant_ligne DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (commande_piece_id) REFERENCES commandes_pieces(id) ON DELETE CASCADE,
    FOREIGN KEY (piece_id) REFERENCES pieces_detachees(id) ON DELETE RESTRICT,
    INDEX idx_commande (commande_piece_id),
    INDEX idx_piece (piece_id)
) ENGINE=InnoDB;

-- ============================================
-- TABLE: echanges_pieces
-- Demandes d'échange de pièces
-- ============================================
CREATE TABLE echanges_pieces (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reference VARCHAR(20) UNIQUE NOT NULL,
    tracking_number VARCHAR(40) DEFAULT NULL,
    user_id BIGINT UNSIGNED NULL,
    piece_ancienne_nom VARCHAR(150) NOT NULL,
    piece_ancienne_description TEXT,
    piece_ancienne_etat ENUM('bon', 'moyen', 'mauvais') DEFAULT 'moyen',
    piece_souhaitee_id BIGINT UNSIGNED,
    marque_vehicule VARCHAR(50),
    modele_vehicule VARCHAR(100),
    annee_vehicule YEAR,
    photos TEXT,
    statut ENUM('en_attente', 'evalution', 'accepte', 'refuse', 'complete') DEFAULT 'en_attente',
    rabais_propose DECIMAL(8,2) DEFAULT 0.00,
    commentaire_admin TEXT,
    date_demande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_evaluation TIMESTAMP NULL,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (piece_souhaitee_id) REFERENCES pieces_detachees(id) ON DELETE SET NULL,
    INDEX idx_reference (reference),
    UNIQUE KEY uq_echanges_pieces_tracking (tracking_number),
    INDEX idx_tracking_echanges_pieces (tracking_number),
    INDEX idx_user (user_id),
    INDEX idx_statut (statut)
) ENGINE=InnoDB;

-- ============================================
-- TABLE: revisions
-- Demandes de révision complète
-- ============================================
CREATE TABLE revisions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reference VARCHAR(20) UNIQUE NOT NULL,
    tracking_number VARCHAR(40) DEFAULT NULL,
        user_id BIGINT UNSIGNED NULL,
    marque_vehicule VARCHAR(50) NOT NULL,
    modele_vehicule VARCHAR(100) NOT NULL,
    annee_vehicule YEAR,
    immatriculation VARCHAR(30),
    kilometrage INT UNSIGNED,
    probleme_description TEXT NOT NULL,
    type_revision ENUM('entretien', 'reparation', 'diagnostic', 'complete') DEFAULT 'complete',
    diagnostic TEXT,
    interventions_prevues TEXT,
    pieces_necessaires TEXT,
    montant_devis DECIMAL(10,2) DEFAULT 0.00,
    montant_final DECIMAL(10,2) DEFAULT 0.00,
    statut ENUM('en_attente', 'diagnostic_en_cours', 'devis_envoye', 'accepte', 'refuse', 'en_intervention', 'termine', 'annule') DEFAULT 'en_attente',
    photos TEXT,
    date_demande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_diagnostic TIMESTAMP NULL,
    date_devis TIMESTAMP NULL,
    date_intervention_debut DATE,
    date_intervention_fin DATE,
    notes TEXT,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_reference (reference),
    UNIQUE KEY uq_revisions_tracking (tracking_number),
    INDEX idx_tracking_revisions (tracking_number),
        INDEX idx_user (user_id),
    INDEX idx_statut (statut),
    INDEX idx_date_demande (date_demande)
) ENGINE=InnoDB;

-- ============================================
-- TABLE: paiements
-- Historique des paiements
-- ============================================
CREATE TABLE paiements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reference VARCHAR(30) UNIQUE NOT NULL,
    tracking_number VARCHAR(40) DEFAULT NULL,
    user_id BIGINT UNSIGNED NULL,
    type_transaction ENUM('commande_voiture', 'location', 'commande_piece', 'revision', 'autre') NOT NULL,
    transaction_id BIGINT UNSIGNED NOT NULL,
    montant DECIMAL(12,2) NOT NULL,
    methode ENUM('mobile_money', 'carte_bancaire', 'virement', 'especes', 'cheque') DEFAULT 'mobile_money',
    operateur VARCHAR(50),
    numero_transaction_externe VARCHAR(100),
    statut ENUM('en_attente', 'reussi', 'echoue', 'rembourse') DEFAULT 'en_attente',
    date_paiement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_confirmation TIMESTAMP NULL,
    notes TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_reference (reference),
    UNIQUE KEY uq_paiements_tracking (tracking_number),
    INDEX idx_tracking_paiements (tracking_number),
    INDEX idx_user (user_id),
    INDEX idx_type_transaction (type_transaction, transaction_id),
    INDEX idx_statut (statut),
    INDEX idx_date (date_paiement)
) ENGINE=InnoDB;

-- ============================================
-- TABLE: logs_activites
-- Traçabilité des actions importantes
-- ============================================
CREATE TABLE logs_activites (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED,
    action VARCHAR(100) NOT NULL,
    table_concernee VARCHAR(50),
    enregistrement_id BIGINT UNSIGNED,
    details TEXT,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    date_action TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
        INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_date (date_action)
) ENGINE=InnoDB;

-- ============================================
-- TABLE: notifications
-- Système de notifications
-- ============================================
CREATE TABLE notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id BIGINT UNSIGNED NULL,
    type VARCHAR(50) NOT NULL,
    titre VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    lien VARCHAR(255),
    lu BOOLEAN DEFAULT FALSE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_lecture TIMESTAMP NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_utilisateur (utilisateur_id),
    INDEX idx_lu (lu),
    INDEX idx_date (date_creation)
) ENGINE=InnoDB;

-- ============================================
-- TABLE: parametres_systeme
-- Configuration globale
-- ============================================
CREATE TABLE parametres_systeme (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cle VARCHAR(100) UNIQUE NOT NULL,
    valeur TEXT NOT NULL,
    type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    description TEXT,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_cle (cle)
) ENGINE=InnoDB;

-- ============================================
-- INSERTION DES DONNÉES INITIALES
-- ============================================

-- Administrateur par défaut (mot de passe: Admin@2025)
INSERT INTO users (nom, prenom, email, telephone, mot_de_passe, role, actif) VALUES
('Admin', 'Système', 'admin@autoimport.com', '+22890000000', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', TRUE);

-- Ports principaux
INSERT INTO ports (nom, code, pays, ville, type, frais_base, delai_moyen_jours, actif) VALUES
('Port Autonome de Lomé', 'LOME', 'Togo', 'Lomé', 'maritime', 500000, 30, TRUE),
('Port de Cotonou', 'COTO', 'Bénin', 'Cotonou', 'maritime', 550000, 32, TRUE),
('Port de Tema', 'TEMA', 'Ghana', 'Tema', 'maritime', 600000, 28, TRUE),
('Port d''Abidjan', 'ABID', 'Côte d''Ivoire', 'Abidjan', 'maritime', 650000, 35, TRUE),
('Livraison Burkina Faso', 'OUAG', 'Burkina Faso', 'Ouagadougou', 'terrestre', 800000, 45, TRUE);

-- Paramètres système
INSERT INTO parametres_systeme (cle, valeur, type, description) VALUES
('taux_tva', '18', 'number', 'Taux de TVA en pourcentage'),
('devise', 'XOF', 'string', 'Devise principale (Franc CFA)'),
('email_contact', 'contact@autoimport.com', 'string', 'Email de contact'),
('telephone_support', '+22890000000', 'string', 'Téléphone support'),
('delai_livraison_pieces', '3', 'number', 'Délai de livraison des pièces en jours'),
('stock_alerte', '5', 'number', 'Seuil d''alerte de stock');

-- ============================================
-- VUES UTILES
-- ============================================

-- Vue: Statistiques des commandes voitures par statut
CREATE OR REPLACE VIEW v_stats_commandes_voitures AS
SELECT 
    statut,
    COUNT(*) as nombre,
    SUM(montant_total) as montant_total,
    SUM(acompte_verse) as total_acomptes,
    SUM(reste_a_payer) as total_reste
FROM commandes_voitures
GROUP BY statut;

-- Vue: Stock des pièces détachées
CREATE OR REPLACE VIEW v_stock_pieces AS
SELECT 
    p.id,
    p.nom,
    p.reference,
    p.marque_compatible,
    p.categorie,
    p.stock,
    p.stock_minimum,
    CASE 
        WHEN p.stock = 0 THEN 'rupture'
        WHEN p.stock <= p.stock_minimum THEN 'faible'
        ELSE 'suffisant'
    END as etat_stock
FROM pieces_detachees p
WHERE p.disponible = TRUE;

-- Vue: Dashboard client
CREATE OR REPLACE VIEW v_dashboard_client AS
SELECT 
    u.id as user_id,
    (SELECT COUNT(*) FROM commandes_voitures cv WHERE cv.user_id = u.id) as nb_commandes_voitures,
    (SELECT COUNT(*) FROM locations l WHERE l.user_id = u.id) as nb_locations,
    (SELECT COUNT(*) FROM commandes_pieces cp WHERE cp.user_id = u.id) as nb_commandes_pieces,
    (SELECT COUNT(*) FROM revisions r WHERE r.user_id = u.id) as nb_revisions,
    (SELECT SUM(montant_total) FROM commandes_voitures cv WHERE cv.user_id = u.id AND cv.statut != 'annule') as total_depenses_voitures,
    (SELECT SUM(montant_total) FROM locations l WHERE l.user_id = u.id AND l.statut != 'annule') as total_depenses_locations,
    (SELECT SUM(montant_total) FROM commandes_pieces cp WHERE cp.user_id = u.id AND cp.statut != 'annule') as total_depenses_pieces
FROM users u
WHERE u.role = 'client';

-- ============================================
-- TRIGGERS
-- ============================================

-- Trigger: Générer référence unique pour commande voiture
DELIMITER //
CREATE TRIGGER before_insert_commande_voiture
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
END//

-- Trigger: Générer référence unique pour location
CREATE TRIGGER before_insert_location
BEFORE INSERT ON locations
FOR EACH ROW
BEGIN
    IF NEW.reference IS NULL OR NEW.reference = '' THEN
        SET NEW.reference = CONCAT('LOC-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
    END IF;
    IF NEW.tracking_number IS NULL OR NEW.tracking_number = '' THEN
        SET NEW.tracking_number = CONCAT('TRK-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
    END IF;
END//

-- Trigger: Générer référence unique pour commande pièce
CREATE TRIGGER before_insert_commande_piece
BEFORE INSERT ON commandes_pieces
FOR EACH ROW
BEGIN
    IF NEW.reference IS NULL OR NEW.reference = '' THEN
        SET NEW.reference = CONCAT('CP-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
    END IF;
    IF NEW.tracking_number IS NULL OR NEW.tracking_number = '' THEN
        SET NEW.tracking_number = CONCAT('TRK-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
    END IF;
END//

-- Trigger: Générer référence unique pour révision
CREATE TRIGGER before_insert_revision
BEFORE INSERT ON revisions
FOR EACH ROW
BEGIN
    IF NEW.reference IS NULL OR NEW.reference = '' THEN
        SET NEW.reference = CONCAT('REV-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
    END IF;
    IF NEW.tracking_number IS NULL OR NEW.tracking_number = '' THEN
        SET NEW.tracking_number = CONCAT('TRK-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
    END IF;
END//

-- Trigger: Générer référence unique pour échange pièce
CREATE TRIGGER before_insert_echange_piece
BEFORE INSERT ON echanges_pieces
FOR EACH ROW
BEGIN
    IF NEW.reference IS NULL OR NEW.reference = '' THEN
        SET NEW.reference = CONCAT('ECH-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
    END IF;
    IF NEW.tracking_number IS NULL OR NEW.tracking_number = '' THEN
        SET NEW.tracking_number = CONCAT('TRK-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
    END IF;
END//

-- Trigger: Générer référence unique pour paiement
CREATE TRIGGER before_insert_paiement
BEFORE INSERT ON paiements
FOR EACH ROW
BEGIN
    IF NEW.reference IS NULL OR NEW.reference = '' THEN
        SET NEW.reference = CONCAT('PAY-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '-', LPAD(FLOOR(RAND() * 1000), 3, '0'));
    END IF;
    IF NEW.tracking_number IS NULL OR NEW.tracking_number = '' THEN
        SET NEW.tracking_number = CONCAT('TRK-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '-', LPAD(FLOOR(RAND() * 10000), 4, '0'));
    END IF;
END//

-- Trigger: Mettre à jour le stock après achat de pièce
CREATE TRIGGER after_insert_ligne_commande_piece
AFTER INSERT ON lignes_commandes_pieces
FOR EACH ROW
BEGIN
    UPDATE pieces_detachees 
    SET stock = stock - NEW.quantite 
    WHERE id = NEW.piece_id;
END//

-- Trigger: Restaurer le stock si commande annulée
CREATE TRIGGER after_update_commande_piece_annulee
AFTER UPDATE ON commandes_pieces
FOR EACH ROW
BEGIN
    IF NEW.statut = 'annule' AND OLD.statut != 'annule' THEN
        UPDATE pieces_detachees p
        INNER JOIN lignes_commandes_pieces lcp ON p.id = lcp.piece_id
        SET p.stock = p.stock + lcp.quantite
        WHERE lcp.commande_piece_id = NEW.id;
    END IF;
END//

-- Trigger: Changer disponibilité voiture après commande
CREATE TRIGGER after_insert_commande_voiture_update_dispo
AFTER INSERT ON commandes_voitures
FOR EACH ROW
BEGIN
    UPDATE voitures 
    SET disponibilite = 'reserve' 
    WHERE id = NEW.voiture_id AND disponibilite = 'disponible';
END//

DELIMITER ;

-- ============================================
-- PROCÉDURES STOCKÉES UTILES
-- ============================================

DELIMITER //

-- Procédure: Rechercher des pièces compatibles
CREATE PROCEDURE sp_rechercher_pieces_compatibles(
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
END//

-- Procédure: Calculer les frais d'importation
CREATE PROCEDURE sp_calculer_frais_import(
    IN p_voiture_id BIGINT,
    IN p_port_id BIGINT,
    OUT p_frais_total DECIMAL(12,2)
)
BEGIN
    DECLARE v_prix_voiture DECIMAL(12,2);
    DECLARE v_frais_port DECIMAL(10,2);
    
    SELECT prix INTO v_prix_voiture FROM voitures WHERE id = p_voiture_id;
    SELECT frais_base INTO v_frais_port FROM ports WHERE id = p_port_id;
    
    -- Calcul simplifié: frais_port + 10% du prix voiture pour douane/transport
    SET p_frais_total = v_frais_port + (v_prix_voiture * 0.10);
END//

-- Procédure: Statistiques mensuelles
CREATE PROCEDURE sp_stats_mensuelles(IN p_mois INT, IN p_annee INT)
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
END//

-- Procédure: Rechercher un enregistrement par tracking_number
CREATE PROCEDURE sp_rechercher_par_tracking(IN p_tracking VARCHAR(40))
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
END//

DELIMITER ;

-- ============================================
-- INDEX SUPPLÉMENTAIRES POUR PERFORMANCES
-- ============================================

-- Index composites pour les recherches fréquentes
CREATE INDEX idx_voitures_recherche ON voitures(marque, modele, annee, disponibilite);
CREATE INDEX idx_pieces_recherche ON pieces_detachees(marque_compatible, modele_compatible, categorie, disponible);
CREATE INDEX idx_commandes_user_statut ON commandes_voitures(user_id, statut);
CREATE INDEX idx_locations_dates_statut ON locations(date_debut, date_fin, statut);

-- ============================================
-- COMMENTAIRES SUR LES TABLES
-- ============================================

ALTER TABLE users COMMENT = 'Gestion des users (clients, agents, administrateurs)';
ALTER TABLE ports COMMENT = 'Ports de destination pour l''importation de véhicules';
ALTER TABLE voitures COMMENT = 'Catalogue des voitures disponibles à la vente/importation';
ALTER TABLE photos_voitures COMMENT = 'Photos des véhicules du catalogue';
ALTER TABLE commandes_voitures COMMENT = 'Commandes d''importation de véhicules';
ALTER TABLE voitures_location COMMENT = 'Flotte de véhicules disponibles à la location';
ALTER TABLE locations COMMENT = 'Réservations et contrats de location';
ALTER TABLE pieces_detachees COMMENT = 'Catalogue des pièces détachées automobiles';
ALTER TABLE commandes_pieces COMMENT = 'Commandes de pièces détachées';
ALTER TABLE lignes_commandes_pieces COMMENT = 'Détails des articles commandés';
ALTER TABLE echanges_pieces COMMENT = 'Demandes d''échange de pièces usagées';
ALTER TABLE revisions COMMENT = 'Demandes de révision et d''entretien de véhicules';
ALTER TABLE paiements COMMENT = 'Historique complet des transactions financières';
ALTER TABLE logs_activites COMMENT = 'Journal d''audit des actions système';
ALTER TABLE notifications COMMENT = 'Notifications users';
ALTER TABLE parametres_systeme COMMENT = 'Configuration globale de l''application';

-- ============================================
-- FIN DU SCRIPT
-- ============================================


DELIMITER //

-- ============================================
-- TRIGGERS POUR LA GESTION DES STOCKS
-- ============================================

-- Alerte automatique stock faible
CREATE TRIGGER after_update_piece_stock_alerte
AFTER UPDATE ON pieces_detachees
FOR EACH ROW
BEGIN
    IF NEW.stock <= NEW.stock_minimum AND OLD.stock > NEW.stock_minimum THEN
        INSERT INTO notifications (user_id, type, titre, message)
        SELECT 
            id,
            'alerte_stock',
            'Stock faible',
            CONCAT('La pièce "', NEW.nom, '" (réf: ', NEW.reference, ') a atteint le seuil d''alerte. Stock actuel: ', NEW.stock)
        FROM users
        WHERE role = 'admin' AND actif = TRUE;
    END IF;
END//

-- Empêcher stock négatif
CREATE TRIGGER before_update_piece_stock_negatif
BEFORE UPDATE ON pieces_detachees
FOR EACH ROW
BEGIN
    IF NEW.stock < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le stock ne peut pas être négatif';
    END IF;
END//

-- ============================================
-- TRIGGERS POUR LES COMMANDES VOITURES
-- ============================================

-- Mise à jour automatique du reste à payer
CREATE TRIGGER before_update_commande_voiture_reste
BEFORE UPDATE ON commandes_voitures
FOR EACH ROW
BEGIN
    SET NEW.reste_a_payer = NEW.montant_total - NEW.acompte_verse;
    
    -- Mise à jour automatique du statut selon paiement
    IF NEW.acompte_verse > 0 AND NEW.reste_a_payer > 0 THEN
        SET NEW.statut = 'paiement_partiel';
    ELSEIF NEW.reste_a_payer <= 0 AND OLD.reste_a_payer > 0 THEN
        SET NEW.statut = 'paye';
        SET NEW.date_paiement_complet = NOW();
    END IF;
END//

-- Notification lors du changement de statut commande
CREATE TRIGGER after_update_commande_voiture_statut
AFTER UPDATE ON commandes_voitures
FOR EACH ROW
BEGIN
    IF NEW.statut != OLD.statut THEN
        INSERT INTO notifications (user_id, type, titre, message, lien)
        VALUES (
            NEW.user_id,
            'commande_update',
            'Mise à jour de votre commande',
            CONCAT('Votre commande ', NEW.reference, ' est maintenant: ', NEW.statut),
            CONCAT('/commandes/', NEW.id)
        );
        
        -- Log de l'activité
        INSERT INTO logs_activites (user_id, action, table_concernee, enregistrement_id, details)
        VALUES (
            NEW.user_id,
            'changement_statut_commande',
            'commandes_voitures',
            NEW.id,
            CONCAT('Statut changé de "', OLD.statut, '" à "', NEW.statut, '"')
        );
    END IF;
END//

-- Remettre la voiture en disponible si commande annulée
CREATE TRIGGER after_update_commande_voiture_annulee
AFTER UPDATE ON commandes_voitures
FOR EACH ROW
BEGIN
    IF NEW.statut = 'annule' AND OLD.statut != 'annule' THEN
        UPDATE voitures 
        SET disponibilite = 'disponible' 
        WHERE id = NEW.voiture_id;
    END IF;
END//

-- ============================================
-- TRIGGERS POUR LES LOCATIONS
-- ============================================

-- Vérifier disponibilité avant insertion location
CREATE TRIGGER before_insert_location_disponibilite
BEFORE INSERT ON locations
FOR EACH ROW
BEGIN
    DECLARE v_disponible BOOLEAN;
    DECLARE v_conflit INT;
    
    SELECT disponible INTO v_disponible 
    FROM voitures_location 
    WHERE id = NEW.voiture_location_id;
    
    IF NOT v_disponible THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Ce véhicule n''est pas disponible à la location';
    END IF;
    
    -- Vérifier chevauchement de dates
    SELECT COUNT(*) INTO v_conflit
    FROM locations
    WHERE voiture_location_id = NEW.voiture_location_id
        AND statut IN ('reserve', 'confirme', 'en_cours')
        AND (
            (NEW.date_debut BETWEEN date_debut AND date_fin)
            OR (NEW.date_fin BETWEEN date_debut AND date_fin)
            OR (date_debut BETWEEN NEW.date_debut AND NEW.date_fin)
        );
    
    IF v_conflit > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Ce véhicule est déjà réservé pour ces dates';
    END IF;
    
    -- Calculer nombre de jours
    SET NEW.nombre_jours = DATEDIFF(NEW.date_fin, NEW.date_debut) + 1;
    SET NEW.montant_location = NEW.nombre_jours * NEW.prix_jour;
    SET NEW.montant_total = NEW.montant_location + NEW.caution + NEW.frais_supplementaires;
END//

-- Marquer véhicule indisponible pendant location
-- Set real start/end dates in locations BEFORE update (cannot modify NEW in AFTER)
CREATE TRIGGER before_update_location_set_dates
BEFORE UPDATE ON locations
FOR EACH ROW
BEGIN
    IF NEW.statut = 'en_cours' AND OLD.statut != 'en_cours' THEN
        SET NEW.date_debut_reelle = NOW();
    ELSEIF NEW.statut = 'termine' AND OLD.statut = 'en_cours' THEN
        SET NEW.date_fin_reelle = NOW();
    END IF;
END//

-- Update vehicle availability in voiture_location after status change
CREATE TRIGGER after_update_location_statut
AFTER UPDATE ON locations
FOR EACH ROW
BEGIN
    IF NEW.statut = 'en_cours' AND OLD.statut != 'en_cours' THEN
        UPDATE voitures_location 
        SET disponible = FALSE 
        WHERE id = NEW.voiture_location_id;
    ELSEIF NEW.statut = 'termine' AND OLD.statut = 'en_cours' THEN
        UPDATE voitures_location 
        SET disponible = TRUE 
        WHERE id = NEW.voiture_location_id;
    ELSEIF NEW.statut = 'annule' THEN
        UPDATE voitures_location 
        SET disponible = TRUE 
        WHERE id = NEW.voiture_location_id;
    END IF;
END//

-- ============================================
-- TRIGGERS POUR LES PAIEMENTS
-- ============================================

-- Log automatique des paiements
CREATE TRIGGER after_insert_paiement
AFTER INSERT ON paiements
FOR EACH ROW
BEGIN
    INSERT INTO logs_activites (user_id, action, table_concernee, enregistrement_id, details)
    VALUES (
        NEW.user_id,
        'paiement_effectue',
        'paiements',
        NEW.id,
        CONCAT('Paiement de ', NEW.montant, ' XOF par ', NEW.methode, ' - Statut: ', NEW.statut)
    );
END//

-- Mise à jour acompte commande voiture après paiement réussi
CREATE TRIGGER after_update_paiement_commande_voiture
AFTER UPDATE ON paiements
FOR EACH ROW
BEGIN
    IF NEW.statut = 'reussi' AND OLD.statut != 'reussi' AND NEW.type_transaction = 'commande_voiture' THEN
        UPDATE commandes_voitures
        SET acompte_verse = acompte_verse + NEW.montant
        WHERE id = NEW.transaction_id;
        
        -- cannot modify NEW in AFTER trigger; update paiement row directly if needed
        UPDATE paiements SET date_confirmation = NOW() WHERE id = NEW.id;
    END IF;
END//

-- ============================================
-- TRIGGERS POUR LES RÉVISIONS
-- ============================================

-- Notification lors de changement statut révision
CREATE TRIGGER after_update_revision_statut
AFTER UPDATE ON revisions
FOR EACH ROW
BEGIN
    IF NEW.statut != OLD.statut THEN
        INSERT INTO notifications (user_id, type, titre, message, lien)
        VALUES (
            NEW.user_id,
            'revision_update',
            'Mise à jour de votre révision',
            CONCAT('Votre demande de révision ', NEW.reference, ' est maintenant: ', NEW.statut),
            CONCAT('/revisions/', NEW.id)
        );
    END IF;
    
    -- Marquer dates automatiquement
    IF NEW.statut = 'devis_envoye' AND OLD.statut != 'devis_envoye' THEN
        SET NEW.date_devis = NOW();
    END IF;
END//

-- ============================================
-- TRIGGERS POUR LA SÉCURITÉ
-- ============================================

-- Empêcher suppression de l'admin principal
CREATE TRIGGER before_delete_user_admin
BEFORE DELETE ON users
FOR EACH ROW
BEGIN
    IF OLD.id = 1 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Impossible de supprimer l''administrateur principal';
    END IF;
END//

-- Log des modifications importantes
CREATE TRIGGER after_update_user_role
AFTER UPDATE ON users
FOR EACH ROW
BEGIN
    IF NEW.role != OLD.role THEN
        INSERT INTO logs_activites (user_id, action, table_concernee, enregistrement_id, details)
        VALUES (
            NEW.id,
            'changement_role',
            'users',
            NEW.id,
            CONCAT('Rôle changé de "', OLD.role, '" à "', NEW.role, '"')
        );
    END IF;
END//

-- ============================================
-- TRIGGERS POUR L'INTÉGRITÉ DES DONNÉES
-- ============================================

-- Validation email format
CREATE TRIGGER before_insert_user_email
BEFORE INSERT ON users
FOR EACH ROW
BEGIN
    IF NEW.email NOT REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Format d''email invalide';
    END IF;
END//

-- Validation dates location
CREATE TRIGGER before_insert_location_dates
BEFORE INSERT ON locations
FOR EACH ROW
BEGIN
    IF NEW.date_fin < NEW.date_debut THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La date de fin doit être postérieure à la date de début';
    END IF;
    
    IF NEW.date_debut < CURDATE() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La date de début ne peut pas être dans le passé';
    END IF;
END//

-- Validation montants négatifs
CREATE TRIGGER before_insert_commande_voiture_montants
BEFORE INSERT ON commandes_voitures
FOR EACH ROW
BEGIN
    IF NEW.prix_voiture < 0 OR NEW.montant_total < 0 OR NEW.acompte_verse < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Les montants ne peuvent pas être négatifs';
    END IF;
    
    IF NEW.acompte_verse > NEW.montant_total THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'L''acompte ne peut pas dépasser le montant total';
    END IF;
END//

-- ============================================
-- TRIGGERS POUR LES LIGNES DE COMMANDES
-- ============================================

-- Calculer montant ligne automatiquement
CREATE TRIGGER before_insert_ligne_commande_piece
BEFORE INSERT ON lignes_commandes_pieces
FOR EACH ROW
BEGIN
    SET NEW.montant_ligne = NEW.quantite * NEW.prix_unitaire;
END//

-- Mettre à jour montant total commande
CREATE TRIGGER after_insert_ligne_update_total
AFTER INSERT ON lignes_commandes_pieces
FOR EACH ROW
BEGIN
    UPDATE commandes_pieces
    SET montant_total = (
        SELECT SUM(montant_ligne)
        FROM lignes_commandes_pieces
        WHERE commande_piece_id = NEW.commande_piece_id
    ) + frais_livraison
    WHERE id = NEW.commande_piece_id;
END//

DELIMITER ;