# projet_qlmr

Application de quiz : utilisateurs, quiz, historique des scores et système de suivi entre joueurs.

## Prérequis

- [XAMPP](https://www.apachefriends.org/) (Apache + MariaDB + PHP 8.1 ou plus)
- Un client HTTP pour tester l'API (Thunder Client, Postman ou curl)

## Structure du projet

```
projet_qlmr/
├── backend/
│   ├── config/
│   │   ├── config.example.php     # modèle de configuration
│   │   └── config.php             # configuration locale (ignorée par Git)
│   ├── public/
│   │   ├── .htaccess              # redirige toutes les requêtes vers index.php
│   │   └── index.php              # point d'entrée : autoload, routes, dispatch
│   ├── sql/
│   │   ├── projet_qlmr.sql        # création de la base, des tables et de l'utilisateur SQL
│   │   └── dataset.sql            # jeu de données de test
│   ├── src/
│   │   ├── controllers/
│   │   │   └── UtilisateurController.php
│   │   ├── models/
│   │   │   └── Utilisateur.php
│   │   ├── Database.php           # connexion PDO (instance unique)
│   │   └── Router.php             # associe méthode + URL à un contrôleur
│   └── uploads/
├── .gitignore
└── README.md
```

## Fonctionnement

```
Requête → .htaccess → public/index.php → Router → Controller → Model → Database (PDO) → MariaDB
                                                      ↓
                                               Réponse JSON
```

| Couche       | Rôle                                                                         |
| ------------ | ---------------------------------------------------------------------------- |
| `Router`     | Choisit le contrôleur selon la méthode HTTP et l'URL                         |
| `Controller` | Valide les données reçues, appelle le model, renvoie le JSON et le code HTTP |
| `Model`      | Contient les requêtes SQL (requêtes préparées)                               |
| `Database`   | Ouvre la connexion PDO à partir de `config/config.php`                       |

## Installation

### 1. Placer le projet dans XAMPP

Cloner (ou copier) le projet dans le dossier `htdocs` :

```bash
cd C:\xampp\htdocs
git clone <url-du-depot> projet_qlmr
```

### 2. Configurer XAMPP

Dans le **XAMPP Control Panel**, démarrer **Apache** et **MySQL**.

Paramètres par défaut de MariaDB sous XAMPP :

| Paramètre    | Valeur      |
| ------------ | ----------- |
| Hôte         | `localhost` |
| Port         | `3306`      |
| Utilisateur  | `root`      |
| Mot de passe | _(vide)_    |

Vérifier ensuite la configuration d'Apache (Control Panel → Apache → **Config** → `httpd.conf`) :

- le module de réécriture d'URL est activé : la ligne suivante ne doit pas commencer par `#`.
  ```apache
  LoadModule rewrite_module modules/mod_rewrite.so
  ```
- les fichiers `.htaccess` sont autorisés dans `htdocs` :
  ```apache
  <Directory "C:/xampp/htdocs">
      AllowOverride All
  </Directory>
  ```

Après toute modification de `httpd.conf`, **redémarrer Apache**.

Vérifier aussi que l'extension `pdo_mysql` est active dans `php.ini` (Config → `php.ini`) : la ligne `extension=pdo_mysql` ne doit pas commencer par `;` (c'est le cas par défaut).

### 3. Créer la base de données

Avec phpMyAdmin (`http://localhost/phpmyadmin`), onglet **Importer**, importer dans cet ordre :

1. `backend/sql/projet_qlmr.sql` : base, tables et utilisateur SQL `qlmr_user`
2. `backend/sql/dataset.sql` : données de test (optionnel)

Pour repartir de zéro : `DROP DATABASE projet_qlmr;` puis réimporter les deux fichiers.

### 4. Configurer la connexion (`backend/config/`)

`config.php` contient les identifiants de la base et n'est **pas versionné**. Le dossier `config/` est ignoré par Git, à l'exception du modèle `config.example.php`.

1. Copier le modèle :
   ```bash
   copy backend\config\config.example.php backend\config\config.php
   ```
2. Renseigner `config.php` :
   ```php
   <?php
   return [
       'host'     => 'localhost',
       'port'     => 3306,
       'dbname'   => 'projet_qlmr',
       'user'     => 'USER_DEFINI_DANS_projet_qlmr.sql',
       'password' => 'PASSWORD_DEFINI_DANS_projet_qlmr.sql',
       'charset'  => 'utf8mb4',
   ];
   ```

Le mot de passe doit être identique à celui défini dans `projet_qlmr.sql`.

## Utilisation de l'API

URL de base (à adapter au nom du dossier) :

```
http://localhost/<chemin_projet>/projet_qlmr/backend/public
```

### Routes disponibles

| Méthode | Route                   | Description             | Codes de réponse |
| ------- | ----------------------- | ----------------------- | ---------------- |
| GET     | `/api/utilisateur`      | Liste des utilisateurs  | 200              |
| GET     | `/api/utilisateur/{id}` | Détail d'un utilisateur | 200, 404         |
| POST    | `/api/utilisateur`      | Création d'un compte    | 201, 409, 422    |

### Comptes du jeu de données

| Pseudo                               | Mot de passe | Rôle           |
| ------------------------------------ | ------------ | -------------- |
| `admin`                              | `admin123`   | Administrateur |
| `quentin`, `gamer42`, `neo`, `pixel` | `test123`    | Utilisateur    |

## Dépannage

| Symptôme                 | Cause probable                               | Solution                                                                |
| ------------------------ | -------------------------------------------- | ----------------------------------------------------------------------- |
| Page 404 HTML d'Apache   | `.htaccess` absent ou ignoré                 | Vérifier qu'il est dans `public/` et que `AllowOverride All` est actif  |
| JSON `Route introuvable` | Route non déclarée ou préfixe d'URL mal géré | Vérifier `index.php` et l'URL de base                                   |
| `Class not found`        | Nom du fichier différent du nom de la classe | `UtilisateurController.php` doit contenir `class UtilisateurController` |
| `Erreur serveur` (500)   | Erreur PHP ou SQL                            | Consulter `C:\xampp\apache\logs\error.log`                              |
| `Access denied for user` | Identifiants incorrects                      | Vérifier `config.php` et l'utilisateur SQL                              |
| `could not find driver`  | `pdo_mysql` désactivé                        | Décommenter `extension=pdo_mysql` dans `php.ini`, redémarrer Apache     |
| Port 3306 déjà utilisé   | Autre instance MySQL                         | Arrêter l'autre service ou changer le port                              |

## Sécurité

- `config/config.php` ne doit jamais être commité.
- Les mots de passe sont stockés hachés (`password_hash`, bcrypt).
- Toutes les requêtes SQL sont préparées.
- L'authentification n'est pas encore implémentée : les routes `PUT` et `DELETE` utilisent un identifiant de test (`$idConnecte = 1`) à remplacer avant toute mise en ligne.
