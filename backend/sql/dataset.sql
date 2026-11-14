-- ============================================================
-- Jeu de données : projet_qlmr
-- ============================================================

USE projet_qlmr;

-- Utilisateurs
INSERT INTO Utilisateur (pseudo, nom, prenom, age, mail, mdp, is_admin) VALUES
('admin', 'Martin', 'Lucas', 22, 'admin@test.fr', '$2y$10$/JCry63LhxF4ynKpGxs7qeoDNVgdCzIhN1fA7OhK6zPKLSECDAzAC', TRUE),
('quentin', 'Serre', 'Quentin', 22, 'quentin@test.fr', '$2y$10$diEZrt4e6AIVdFUNbMhwPOrlXJxQs1ylJ1naNryVJqx26QDwFII32', FALSE),
('gamer42', 'Dupont', 'Emma', 20, 'emma@test.fr', '$2y$10$diEZrt4e6AIVdFUNbMhwPOrlXJxQs1ylJ1naNryVJqx26QDwFII32', FALSE),
('neo', 'Bernard', 'Nathan', 21, 'nathan@test.fr', '$2y$10$diEZrt4e6AIVdFUNbMhwPOrlXJxQs1ylJ1naNryVJqx26QDwFII32', FALSE),
('pixel', 'Morel', 'Jade', 19, 'jade@test.fr', '$2y$10$diEZrt4e6AIVdFUNbMhwPOrlXJxQs1ylJ1naNryVJqx26QDwFII32', FALSE);

-- Quiz
INSERT INTO Quiz (nom, categorie, json, description, etat, id_utilisateur) VALUES
('Culture Internet', 'Internet',
'{
  "questions": [
    {
      "question": "Qui a créé le World Wide Web ?",
      "reponses": {
        "A": "Bill Gates",
        "B": "Tim Berners-Lee",
        "C": "Steve Jobs",
        "D": "Mark Zuckerberg"
      },
      "bonne_reponse": "B"
    },
    {
      "question": "Que signifie HTML ?",
      "reponses": {
        "A": "HyperText Markup Language",
        "B": "HighText Machine Language",
        "C": "Hyper Transfer Main Link",
        "D": "Home Tool Markup Language"
      },
      "bonne_reponse": "A"
    },
    {
      "question": "Quel navigateur est développé par Mozilla ?",
      "reponses": {
        "A": "Chrome",
        "B": "Edge",
        "C": "Firefox",
        "D": "Safari"
      },
      "bonne_reponse": "C"
    }
  ]
}',
'Testez vos connaissances sur Internet.', 'publie', 1),

('Jeux vidéo', 'Gaming',
'{
  "questions": [
    {
      "question": "Quel personnage est la mascotte de Nintendo ?",
      "reponses": {
        "A": "Sonic",
        "B": "Mario",
        "C": "Crash",
        "D": "Link"
      },
      "bonne_reponse": "B"
    },
    {
      "question": "Quel studio a développé Minecraft ?",
      "reponses": {
        "A": "Mojang",
        "B": "Ubisoft",
        "C": "Valve",
        "D": "Rockstar"
      },
      "bonne_reponse": "A"
    },
    {
      "question": "En quelle année est sortie la première PlayStation ?",
      "reponses": {
        "A": "1990",
        "B": "1992",
        "C": "1994",
        "D": "1998"
      },
      "bonne_reponse": "C"
    }
  ]
}',
'Un quiz sur les jeux vidéo.', 'publie', 2),

('Culture générale', 'Culture',
'{
  "questions": [
    {
      "question": "Quelle est la capitale du Japon ?",
      "reponses": {
        "A": "Pékin",
        "B": "Séoul",
        "C": "Tokyo",
        "D": "Bangkok"
      },
      "bonne_reponse": "C"
    },
    {
      "question": "Combien de continents existe-t-il ?",
      "reponses": {
        "A": "5",
        "B": "6",
        "C": "7",
        "D": "8"
      },
      "bonne_reponse": "C"
    },
    {
      "question": "Quelle planète est surnommée la planète rouge ?",
      "reponses": {
        "A": "Vénus",
        "B": "Mars",
        "C": "Jupiter",
        "D": "Mercure"
      },
      "bonne_reponse": "B"
    }
  ]
}',
'Questions de culture générale.', 'publie', 3),

('Informatique', 'Informatique',
'{
  "questions": [
    {
      "question": "Que signifie CPU ?",
      "reponses": {
        "A": "Central Processing Unit",
        "B": "Computer Personal Unit",
        "C": "Central Program Utility",
        "D": "Control Processing User"
      },
      "bonne_reponse": "A"
    },
    {
      "question": "Quel langage est utilisé pour styliser une page Web ?",
      "reponses": {
        "A": "HTML",
        "B": "CSS",
        "C": "SQL",
        "D": "Python"
      },
      "bonne_reponse": "B"
    },
    {
      "question": "Quel système de gestion de base de données utilise le langage SQL ?",
      "reponses": {
        "A": "MariaDB",
        "B": "Photoshop",
        "C": "Firefox",
        "D": "Windows"
      },
      "bonne_reponse": "A"
    }
  ]
}',
'Testez vos connaissances informatiques.', 'brouillon', 2);

-- Historique
INSERT INTO Historique (date_, score, id_utilisateur, id_quiz) VALUES
('2026-11-01 14:30:00', 8, 2, 1),
('2026-11-02 15:00:00', 6, 3, 1),
('2026-11-03 10:15:00', 10, 4, 2),
('2026-11-04 16:45:00', 7, 5, 3),
('2026-11-05 11:20:00', 9, 2, 2),
('2026-11-06 09:30:00', 5, 3, 3);

-- Suivre
INSERT INTO suivre (id_utilisateur, id_utilisateur_1, etat) VALUES
(2, 3, 'accepte'),
(2, 4, 'accepte'),
(2, 5, 'en_attente'),
(3, 2, 'accepte'),
(3, 4, 'accepte'),
(4, 5, 'en_attente'),
(5, 2, 'accepte');