# Application de Raccourcissement d'URL (MyAnaPro)

Ce projet est une application monolithique de raccourcissement d'URL développée avec **Laravel 11**, **PHP 8.3**, **Tailwind CSS v4** et **SQLite**. L'environnement de développement est entièrement conteneurisé grâce à Docker.

## Prérequis

- [Docker](https://www.docker.com/) et Docker Compose doivent être installés et lancés sur votre machine.

## Démarrer le projet

### 1. Cloner et configurer l'environnement
Assurez-vous d'avoir un fichier `.env` à la racine (copiez `.env.example` si nécessaire). 
*Note : Le serveur d'e-mail local Mailpit est déjà configuré dans le projet (`MAIL_HOST=mailpit`).*

### 2. Lancer l'environnement Docker
À la racine du projet, exécutez la commande suivante pour construire et démarrer l'ensemble des conteneurs (Nginx, PHP, Node.js et Mailpit) en arrière-plan :

```bash
docker compose up -d --build
```

### 3. Installer les dépendances front-end et compiler
L'application utilise Tailwind CSS. Pour installer les paquets NPM et compiler les assets, exécutez :

```bash
docker compose exec nodejs npm install
docker compose exec nodejs npm run build
```

### 4. Préparer la base de données
L'application utilise SQLite. Exécutez les migrations pour créer la structure de la base de données :

```bash
docker compose exec php php artisan migrate
```

Si vous souhaitez **générer des données de test** (10 utilisateurs avec des liens aléatoires, et un compte de test `test@example.com` / `password`), lancez :

```bash
docker compose exec php php artisan db:seed
```

## Accéder à l'application

Une fois ces étapes terminées, vos services sont accessibles aux adresses suivantes :
- **Application Web** : [http://localhost:8080](http://localhost:8080)
- **Boîte de réception locale (Mailpit)** : [http://localhost:8025](http://localhost:8025)

---

## Lancer les Tests

Des tests unitaires et fonctionnels complets ont été rédigés pour couvrir l'authentification, la gestion des liens, les redirections, et la commande de nettoyage.

Pour exécuter la suite de tests complète, lancez :

```bash
docker compose exec php php artisan test
```

## Commande de Nettoyage (Tâche planifiée)

L'application dispose d'une commande Artisan personnalisée qui supprime les liens inactifs depuis plus de 30 jours et envoie un rapport par e-mail aux propriétaires de ces liens.

Pour tester cette commande manuellement :

```bash
docker compose exec php php artisan links:clean
```
*(Allez ensuite vérifier l'interface de Mailpit sur `http://localhost:8025` pour voir les e-mails envoyés !)*

---
