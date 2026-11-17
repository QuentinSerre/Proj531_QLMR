<?php

header('Content-Type: application/json; charset=utf-8');

// Autoload : le nom du fichier doit être égal au nom de la classe
spl_autoload_register(function (string $class) {
  $base = __DIR__ . '/../src/';
  foreach (['', 'Controllers/', 'Models/'] as $dir) {
    $file = $base . $dir . $class . '.php';
    if (is_file($file)) {
      require $file;
      return;
    }
  }
});

// Toute erreur non gérée → JSON 500, sans détails pour le client
set_exception_handler(function (Throwable $e) {
  error_log($e->getMessage());
  http_response_code(500);
  echo json_encode(['error' => 'Erreur serveur']);
});

$router = new Router();

$router->add('GET',    '/api/utilisateur',      [UtilisateurController::class, 'getAllUsers']);
$router->add('GET',    '/api/utilisateur/{id}', [UtilisateurController::class, 'getUser']);
$router->add('POST',   '/api/utilisateur',      [UtilisateurController::class, 'createUser']);
// $router->add('PUT',    '/api/utilisateur/{id}', [UtilisateurController::class, 'updateUser']);
// $router->add('DELETE', '/api/utilisateur/{id}', [UtilisateurController::class, 'deleteUser']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
