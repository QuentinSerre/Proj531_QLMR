<?php

class Utilisateur
{
  // GET /api/utilisateur
  public static function getAll(): array
  {
    $sql = 'SELECT
              id_utilisateur,
              pseudo,
              pp_path
            FROM Utilisateur';
    return Database::getConnection()->query($sql)->fetchAll();
  }

  // GET /api/utilisateur/{id}
  public static function get(int $id): array|false
  {
    $sql = 'SELECT
              id_utilisateur,
              pseudo,
              pp_path
            FROM Utilisateur
            WHERE id_utilisateur = :id_utilisateur';
    $stmt = Database::getConnection()->prepare($sql);
    $stmt->execute(['id_utilisateur' => $id]);
    return $stmt->fetch();
  }

  // POST /api/utilisateur
  public static function create(array $user): int
  {
    $sql = 'INSERT INTO Utilisateur (mail, mdp, pseudo, nom, prenom, age)
              VALUES (:mail, :mdp, :pseudo, :nom, :prenom, :age)';

    $pdo = Database::getConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      'mail'   => $user['mail'],
      'mdp'    => $user['mdp'],
      'pseudo' => $user['pseudo'],
      'nom'    => $user['nom'],
      'prenom' => $user['prenom'],
      'age'    => $user['age'],
    ]);

    return (int) $pdo->lastInsertId();
  }

  // PUT /api/utilisateur/{id}
  // public static function update(array $user): int
  // {
  //   $sql = 'UPDATE Utilisateur
  //           SET mail = :mail,
  //               mdp = :mdp
  //               pseudo = :pseudo
  //               nom = :nom
  //               prenom = :prenom
  //               age = :age
  //               is_admin = :is_admin
  //               pp_path = :pp_path
  //           WHERE id_utilisateur = :id_utilisateur';

  //   $pdo = Database::getConnection();
  //   $stmt = $pdo->prepare($sql);
  //   $stmt->execute([
  //     'id_utilisateur'   => $user['id_utilisateur'],
  //     'mail'   => $user['mail'],
  //     'mdp'    => $user['mdp'],
  //     'pseudo' => $user['pseudo'],
  //     'nom'    => $user['nom'],
  //     'prenom' => $user['prenom'],
  //     'age'    => $user['age'],
  //     'is_amdin'    => $user['is_amdin'],
  //     'pp_path'    => $user['pp_path'],
  //   ]);

  //   return $stmt->rowCount();
  // }
}
