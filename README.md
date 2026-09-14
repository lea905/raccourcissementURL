# Application de Raccourcissement d'URL (MyAnaPro)

Ce projet est une application monolithique de raccourcissement d'URL développée avec **Laravel 13**, **PHP 8.5**, **Tailwind CSS v4** et **SQLite**. L'environnement de développement est entièrement conteneurisé grâce à Docker.

## Prérequis

- [Docker](https://www.docker.com/) et Docker Compose doivent être installés et lancés sur votre machine.

## Démarrer le projet

### 1. Lancer l'environnement Docker
À la racine du projet, exécutez la commande suivante pour construire et démarrer l'ensemble des conteneurs (Nginx, PHP et Node.js) en arrière-plan :

```bash
docker compose up -d --build
```

### 2. Installer les dépendances front-end
L'application utilise Tailwind CSS. Pour installer les paquets NPM, utilisez le conteneur Node.js prévu à cet effet :

```bash
docker compose exec nodejs npm install
```

### 3. Compiler les assets (Tailwind CSS)
Pour compiler le CSS/JS en mode développement (avec rechargement automatique) :

```bash
docker compose exec nodejs npm run dev
```


### 4. Migrer la base de données
La base de données SQLite est prête, mais il faut exécuter les migrations pour créer les tables de base de Laravel :

```bash
docker compose exec php php artisan migrate
```

## Accéder à l'application

Une fois ces étapes terminées, l'application est accessible depuis votre navigateur à l'adresse suivante :
 **[http://localhost:8080](http://localhost:8080)**

---

## Commandes utiles au quotidien

Puisque l'application tourne sous Docker, vous ne devez pas lancer les commandes sur votre machine hôte, mais à l'intérieur des conteneurs :

- **Exécuter une commande Artisan** (depuis le conteneur PHP) :
  ```bash
  docker compose exec php php artisan make:controller MonController
  ```
- **Installer un paquet PHP via Composer** (depuis le conteneur PHP) :
  ```bash
  docker compose exec php composer require nom/du-paquet
  ```
- **Voir les logs des conteneurs** :
  ```bash
  docker compose logs -f
  ```
- **Éteindre les conteneurs** :
  ```bash
  docker compose down
  ```
