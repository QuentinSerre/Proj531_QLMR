<?php

/**
 * UtilisateurController
 * Reçoit la requête, valide, appelle le Model, répond en JSON.
 *
 * Fichier : backend/src/Controllers/UtilisateurController.php
 */
class UtilisateurController extends BaseController
{

    // -------------------
    //  Public functions
    // -------------------

    // GET /api/utilisateur
    public static function getAllUsers(): void
    {
        self::json(Utilisateur::getAll());
    }

    // GET /api/utilisateur/{id}
    public static function getUser(array $params): void
    {
        $utilisateur = Utilisateur::get((int) $params['id']);

        if (!$utilisateur) {
            self::json(['error' => 'Utilisateur introuvable'], 404);
            return;
        }

        self::json($utilisateur);
    }

    // POST /api/utilisateur
    // Corps : { "pseudo", "mail", "mdp", "nom"?, "prenom"?, "age"? }
    public static function createUser(): void
    {
        $data = self::body();

        // Champs obligatoires
        if (empty($data['pseudo']) || empty($data['mail']) || empty($data['mdp'])) {
            self::json(['error' => 'pseudo, mail et mdp sont obligatoires'], 422);
            return;
        }
        if (!filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
            self::json(['error' => 'Adresse e-mail invalide'], 422);
            return;
        }
        if (strlen($data['mdp']) < 8) {
            self::json(['error' => 'Mot de passe : 8 caractères minimum'], 422);
            return;
        }

        try {
            $id = Utilisateur::create([
                'pseudo' => trim($data['pseudo']),
                'mail'   => trim($data['mail']),
                'mdp'    => password_hash($data['mdp'], PASSWORD_DEFAULT), // jamais en clair
                'nom'    => $data['nom'] ?? null,
                'prenom' => $data['prenom'] ?? null,
                'age'    => $data['age'] ?? null,
            ]);
        } catch (PDOException $e) {
            // 23000 = contrainte UNIQUE violée (mail ou pseudo déjà pris)
            if ($e->getCode() == 23000) {
                self::json(['error' => 'Mail ou pseudo déjà utilisé'], 409);
                return;
            }
            throw $e;
        }

        self::json(['id_utilisateur' => $id], 201);
    }

    // PUT /api/utilisateur/{id}
    // Corps : { "pseudo", "mail", "nom"?, "prenom"?, "age"? }
    // public static function updateUser(array $params): void
    // {
    //     $idConnecte = 1; // TODO : remplacer par l'utilisateur connecté (Auth)
    //     $id = (int) $params['id_utilsateur'];

    //     if (!Utilisateur::get($id)) {
    //         self::json(['error' => 'Utilisateur introuvable'], 404);
    //         return;
    //     }

    //     // On ne modifie que son propre compte
    //     if ($id !== $idConnecte) {
    //         self::json(['error' => 'Accès refusé'], 403);
    //         return;
    //     }

    //     $data = self::body();

    //     if (empty($data['pseudo']) || empty($data['mail'])) {
    //         self::json(['error' => 'pseudo et mail sont obligatoires'], 422);
    //         return;
    //     }
    //     if (!filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
    //         self::json(['error' => 'Adresse e-mail invalide'], 422);
    //         return;
    //     }

    //     try {
    //         Utilisateur::update($id, [
    //             'pseudo' => trim($data['pseudo']),
    //             'mail'   => trim($data['mail']),
    //             'nom'    => $data['nom'] ?? null,
    //             'prenom' => $data['prenom'] ?? null,
    //             'age'    => $data['age'] ?? null,
    //         ]);
    //     } catch (PDOException $e) {
    //         if ($e->getCode() == 23000) {
    //             self::json(['error' => 'Mail ou pseudo déjà utilisé'], 409);
    //             return;
    //         }
    //         throw $e;
    //     }

    //     self::json(['success' => true]);
    // }

    // DELETE /api/utilisateur/{id}
    // public static function deleteUser(array $params): void
    // {
    //     $idConnecte = 1; // TODO : remplacer par l'utilisateur connecté (Auth)
    //     $id = (int) $params['id'];

    //     if (!Utilisateur::get($id)) {
    //         self::json(['error' => 'Utilisateur introuvable'], 404);
    //         return;
    //     }

    //     if ($id !== $idConnecte) {
    //         self::json(['error' => 'Accès refusé'], 403);
    //         return;
    //     }

    //     Utilisateur::delete($id);

    //     http_response_code(204); // succès, rien à renvoyer
    // }

    // DELETE /api/utilisateur/{id}
    // public static function deleteUser(array $params): void
    //{
    //}
}
