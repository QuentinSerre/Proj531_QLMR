<?php

/**
 * Example : MODÈLE de model à dupliquer pour chaque table.
 *
 * Pour créer un nouveau model (ex. Example) :
 *   1. Copier ce fichier et le renommer <nom>.php
 *      (le nom du fichier doit être identique au nom de la classe)
 *   2. Remplacer "Example" par le nom de la table, et adapter les colonnes
 *   3. Garder la même signature des méthodes que celles appelées par le contrôleur
 *
 * Rôle d'un model :
 *   - contenir TOUTES les requêtes SQL de la table (le contrôleur n'en contient aucune)
 *   - utiliser uniquement des requêtes préparées (prepare + execute)
 *   - retourner des données brutes (tableaux, ID), sans code HTTP ni JSON
 *
 * Règles de sécurité :
 *   - jamais de variable collée dans le SQL : toujours un paramètre nommé (:nom)
 *   - toujours lister les colonnes (pas de SELECT *), pour ne jamais renvoyer
 *     une colonne sensible par accident (ex. mdp)
 *
 * Fichier : backend/src/models/Example.php
 */
class Example
{
  // GET /api/example
  // Pas de paramètre : query() suffit, pas besoin de prepare().
  public static function getAll(): array
  {
    $sql = 'SELECT
              id_example,
              nom,
              description
            FROM Example';

    return Database::getConnection()->query($sql)->fetchAll();
  }

  // GET /api/example/{id}
  // Retourne la ligne (tableau associatif) ou false si elle n'existe pas.
  public static function get(int $id): array|false
  {
    // :id_example est un paramètre nommé : PDO envoie la requête et la valeur
    // séparément, donc la valeur ne peut jamais être interprétée comme du SQL.
    //
    // id_utilisateur est sélectionné car le contrôleur s'en sert pour vérifier
    // le propriétaire (403). Le retirer si la table n'a pas de propriétaire.
    $sql = 'SELECT
              id_example,
              nom,
              description,
              id_utilisateur
            FROM Example
            WHERE id_example = :id_example';

    $stmt = Database::getConnection()->prepare($sql);
    $stmt->execute(['id_example' => $id]);

    return $stmt->fetch(); // false si aucune ligne
  }

  // POST /api/example
  // Retourne l'ID de la ligne créée (la colonne doit être AUTO_INCREMENT).
  public static function create(array $example): int
  {
    $sql = 'INSERT INTO Example (nom, description, id_utilisateur)
            VALUES (:nom, :description, :id_utilisateur)';

    // On garde l'objet PDO dans une variable : lastInsertId() s'appelle sur
    // la connexion (pas sur $stmt) et juste après l'INSERT.
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      'nom'            => $example['nom'],
      'description'    => $example['description'],
      'id_utilisateur' => $example['id_utilisateur'],
    ]);

    // lastInsertId() retourne une chaîne ("12") : on la convertit en entier.
    return (int) $pdo->lastInsertId();
  }

  // PUT /api/example/{id}
  // L'ID vient de l'URL (paramètre $id), jamais du tableau reçu du client.
  // Le contrôleur envoie l'objet complet (existant fusionné avec les champs reçus),
  // donc toutes les colonnes modifiables sont mises à jour.
  //
  // Colonnes volontairement absentes du SET : id_utilisateur (propriétaire),
  // et tout champ sensible (mdp, is_admin) qui doit passer par une route dédiée.
  public static function update(int $id, array $example): void
  {
    // Attention aux virgules entre les colonnes du SET (pas après la dernière).
    $sql = 'UPDATE Example
            SET nom = :nom,
                description = :description
            WHERE id_example = :id_example';

    $stmt = Database::getConnection()->prepare($sql);
    $stmt->execute([
      'id_example'  => $id,
      'nom'         => $example['nom'],
      'description' => $example['description'],
    ]);

    // Pas de return : le contrôleur a déjà vérifié que la ligne existe.
    // rowCount() compterait seulement les lignes réellement modifiées
    // (0 si les valeurs envoyées sont identiques à celles en base).
  }

  // DELETE /api/example/{id}
  public static function delete(int $id): void
  {
    $sql = 'DELETE FROM Example WHERE id_example = :id_example';

    $stmt = Database::getConnection()->prepare($sql);
    $stmt->execute(['id_example' => $id]);

    // Les lignes liées (clés étrangères) sont supprimées par ON DELETE CASCADE.
  }
}
