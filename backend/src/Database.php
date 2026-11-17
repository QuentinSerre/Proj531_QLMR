<?php

/**
 * Classe Database pour la gestion de la connexion singleton à la base de données MySQL via PDO.
 */
class Database
{
  private static ?PDO $pdo = null;

  /**
   * Récupère l'instance de la connexion PDO (Singleton).
   * 
   * @return PDO
   * @throws PDOException
   */
  public static function getConnection(): PDO
  {
    if (self::$pdo === null) {
      $config = require __DIR__ . '/../config/config.php';

      $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
        $config['host'],
        $config['port'],
        $config['dbname'],
        $config['charset']
      );

      try {
        self::$pdo = new PDO($dsn, $config['user'], $config['password'], [
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
      } catch (PDOException $e) {
        error_log('Erreur BDD : ' . $e->getMessage());
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Erreur serveur']);
        exit;
      }
    }

    return self::$pdo;
  }
}
