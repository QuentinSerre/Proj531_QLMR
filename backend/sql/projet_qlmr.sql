-- ============================================================
-- Base de données : projet_qlmr
-- ============================================================

SET @user = 'USER';     -- A MODIFIER
SET @pass = 'PASSWORD'; -- A MODIFIER

SET @host = 'localhost';

CREATE DATABASE IF NOT EXISTS projet_qlmr
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE projet_qlmr;

-- ------------------------------------------------------------
-- Tables
-- ------------------------------------------------------------

CREATE TABLE Utilisateur(
   id_utilisateur INT AUTO_INCREMENT,
   pseudo VARCHAR(50) NOT NULL UNIQUE,
   pp_path VARCHAR(200),
   nom VARCHAR(50),
   prenom VARCHAR(50),
   age INT,
   mail VARCHAR(250) NOT NULL UNIQUE,
   mdp VARCHAR(255) NOT NULL,
   is_admin BOOLEAN DEFAULT FALSE,
   PRIMARY KEY(id_utilisateur)
) ENGINE=InnoDB;

CREATE TABLE Quiz(
   id_quiz INT AUTO_INCREMENT,
   nom VARCHAR(100) NOT NULL,
   id_connexion VARCHAR(50),
   categorie VARCHAR(50),
   json TEXT,
   description VARCHAR(500),
   etat VARCHAR(50),
   id_utilisateur INT NOT NULL,
   PRIMARY KEY(id_quiz),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Historique(
   id_historique INT AUTO_INCREMENT,
   date_ DATETIME NOT NULL,
   score INT NOT NULL,
   id_utilisateur INT NOT NULL,
   id_quiz INT NOT NULL,
   PRIMARY KEY(id_historique),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur)ON DELETE CASCADE,
   FOREIGN KEY(id_quiz) REFERENCES Quiz(id_quiz) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE suivre(
   id_utilisateur INT,
   id_utilisateur_1 INT,
   etat VARCHAR(50),
   PRIMARY KEY(id_utilisateur, id_utilisateur_1),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE,
   FOREIGN KEY(id_utilisateur_1) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------
-- Création utilisateur applicatif
-- ---------------------------------

SET @sql = CONCAT('CREATE USER IF NOT EXISTS ', QUOTE(@user), '@', QUOTE(@host),
                  ' IDENTIFIED BY ', QUOTE(@pass));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Droits sur la base
SET @sql = CONCAT('GRANT SELECT, INSERT, UPDATE, DELETE ON projet_qlmr.* TO ',
                  QUOTE(@user), '@', QUOTE(@host));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

FLUSH PRIVILEGES;
