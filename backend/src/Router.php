<?php

class Router
{
  private array $routes = [];

  public function add(string $method, string $pattern, array $handler): void
  {
    $this->routes[] = [$method, $pattern, $handler];
  }

  public function dispatch(string $method, string $uri): void
  {
    $path = parse_url($uri, PHP_URL_PATH);

    // Retire le préfixe XAMPP (ex. /projet_qlmr/backend/public)
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    if ($base !== '' && str_starts_with($path, $base)) {
      $path = substr($path, strlen($base));
    }

    foreach ($this->routes as [$m, $pattern, $handler]) {
      // /api/utilisateur/{id}  →  regex avec capture nommée
      $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $pattern) . '$#';

      if ($m === $method && preg_match($regex, $path, $matches)) {
        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        $handler($params);
        return;
      }
    }

    http_response_code(404);
    echo json_encode(['error' => 'Route introuvable']);
  }
}
