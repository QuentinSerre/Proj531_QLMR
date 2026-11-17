<?php

/**
 * ExampleController : MODÈLE de contrôleur à dupliquer pour chaque ressource.
 *
 * Pour créer un nouveau contrôleur (ex. ExampleController) :
 *   1. Copier ce fichier et le renommer <nom>Controller.php
 *      (le nom du fichier doit être identique au nom de la classe)
 *   2. ne pas oublier extends BaseController
 *   3. Adapter les champs de validation et les champs transmis au model
 *   4. Déclarer les routes dans public/index.php
 *
 * Fichier : backend/src/controllers/ExampleController.php
 */
class ExampleController extends BaseController
{

  // ============================================================
  //  Fonctions publiques (une par route déclarée dans index.php)
  // ============================================================

  /**
   * GET /api/example
   * Retourne la liste de tous les éléments.
   * Route : $router->add('GET', '/api/example', [ExampleController::class, 'getAll']);
   */
  public static function getAll(): void
  {
    // Le model fait la requête SQL, le contrôleur ne fait que répondre.
    self::json(Example::getAll());
  }

  /**
   * GET /api/example/{id}
   * $params vient du Router : ['id' => '12'] (toujours des chaînes de caractères).
   * Route : $router->add('GET', '/api/example/{id}', [ExampleController::class, 'get']);
   */
  public static function get(array $params): void
  {
    // (int) : convertit en entier, ce qui neutralise les valeurs inattendues ("abc" devient 0)
    $example = Example::get((int) $params['id']);

    // Vérifie que l'élément existe : 404 = introuvable
    if (!$example) {
      self::json(['error' => 'Élément introuvable'], 404);
      return;
    }

    self::json($example);
  }

  /**
   * POST /api/example
   * Corps attendu (JSON) : { "nom": "...", "description"?: "..." }
   * Route : $router->add('POST', '/api/example', [ExampleController::class, 'create']);
   */
  public static function create(): void
  {
    $data = self::body();

    // --- Validation : on ne fait jamais confiance aux données du client ---

    // Champs obligatoires
    if (empty($data['nom'])) {
      self::json(['error' => 'nom est obligatoire'], 422); // 422 = données invalides
      return;
    }
    // Longueur maximale (doit correspondre à la taille de la colonne en base)
    if (mb_strlen($data['nom']) > 100) {
      self::json(['error' => 'nom : 100 caractères maximum'], 422);
      return;
    }

    try {
      // On ne transmet au model QUE les champs autorisés (jamais $data brut),
      // sinon le client pourrait envoyer des champs qu'il n'a pas le droit de définir
      // (ex. "is_admin": 1).
      // trim() retire les espaces superflus ; "?? null" met null si le champ est absent.
      $id = Example::create([
        'nom'         => trim($data['nom']),
        'description' => $data['description'] ?? null,
      ]);
    } catch (PDOException $e) {
      // 23000 = contrainte violée en base (ex. UNIQUE : doublon)
      if ($e->getCode() == 23000) {
        self::json(['error' => 'Cet élément existe déjà'], 409); // 409 = conflit
        return;
      }
      // Autre erreur SQL : on la relance, le gestionnaire global d'index.php renverra un 500
      throw $e;
    }

    // 201 = ressource créée ; on renvoie l'ID généré (lastInsertId dans le model)
    self::json(['id_example' => $id], 201);
  }

  /**
   * PUT /api/example/{id}
   * Corps attendu (JSON) : { "nom"?: "...", "description"?: "..." }
   * Seuls les champs envoyés sont modifiés, les autres gardent leur valeur en base.
   * Route : $router->add('PUT', '/api/example/{id}', [ExampleController::class, 'update']);
   */
  public static function update(array $params): void
  {
    $idConnecte = 1; // TODO : remplacer par l'utilisateur connecté (Auth::requireLogin())
    $id = (int) $params['id'];

    // On charge l'élément existant : sert à vérifier qu'il existe
    // ET de base pour la fusion avec les champs reçus.
    $example = Example::get($id);
    if (!$example) {
      self::json(['error' => 'Élément introuvable'], 404);
      return;
    }

    // Vérification des droits : 403 = connecté, mais pas autorisé.
    // Pour une ressource appartenant à un utilisateur (ex. un quiz) :
    //   if ((int) $example['id_utilisateur'] !== $idConnecte) { ... }
    // Pour le compte d'un utilisateur lui-même :
    //   if ($id !== $idConnecte) { ... }
    if ((int) $example['id_utilisateur'] !== $idConnecte) {
      self::json(['error' => 'Accès refusé'], 403);
      return;
    }

    // Fusion : on garde uniquement les champs MODIFIABLES parmi ceux reçus,
    // puis on les écrase sur l'objet chargé depuis la base.
    $modifiables = ['nom', 'description'];
    $recus = array_intersect_key(self::body(), array_flip($modifiables));
    $example = array_merge($example, $recus);

    // Validation sur l'objet final (après fusion)
    $example['nom'] = trim((string) $example['nom']);
    if ($example['nom'] === '') {
      self::json(['error' => 'nom est obligatoire'], 422);
      return;
    }

    try {
      // L'ID vient de l'URL, JAMAIS du corps de la requête
      // (sinon on pourrait modifier la ligne d'un autre).
      Example::update($id, $example);
    } catch (PDOException $e) {
      if ($e->getCode() == 23000) {
        self::json(['error' => 'Cet élément existe déjà'], 409);
        return;
      }
      throw $e;
    }

    self::json(['success' => true]);
  }

  /**
   * DELETE /api/example/{id}
   * Route : $router->add('DELETE', '/api/example/{id}', [ExampleController::class, 'delete']);
   */
  public static function delete(array $params): void
  {
    $idConnecte = 1; // TODO : remplacer par l'utilisateur connecté (Auth::requireLogin())
    $id = (int) $params['id'];

    $example = Example::get($id);
    if (!$example) {
      self::json(['error' => 'Élément introuvable'], 404);
      return;
    }

    if ((int) $example['id_utilisateur'] !== $idConnecte) {
      self::json(['error' => 'Accès refusé'], 403);
      return;
    }

    Example::delete($id);

    // 204 = succès, aucun contenu à renvoyer (donc pas de json())
    http_response_code(204);
  }
}
