<?php

/**
 * BaseController
 * Contrôleur recensant toutes les fonctions communes
 * à tous les controleur
 *
 * Fichier : backend/src/Controllers/BaseController.php
 */
abstract class BaseController
{
  // -------------------
  //  Protected functions
  // -------------------

  // Lit le JSON envoyé par React ($_POST est vide avec fetch + JSON.stringify)
  protected static function body(): array
  {
    $data = json_decode(file_get_contents('php://input'), true);
    return is_array($data) ? $data : [];
  }

  // Envoie une réponse JSON avec le code HTTP voulu
  protected static function json(mixed $data, int $status = 200): void
  {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
  }

  // -------------------
  //  Private functions
  // -------------------

  // -------------------
  //  Public functions
  // -------------------
}
