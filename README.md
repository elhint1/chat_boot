# Projet Chatbot avec PHP et MySQL

## Introduction

Ce projet est un chatbot développé avec PHP, MySQL et des API d'IA comme OpenAI, Claude et HuggingFace. Il permet aux utilisateurs de discuter avec le chatbot, et les messages sont stockés dans une base de données MySQL. Les utilisateurs peuvent s'inscrire, se connecter, discuter avec le chatbot, et leurs conversations sont enregistrées pour une utilisation future.

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :

- PHP (version 7.4 ou supérieure)
- MySQL
- Serveur Web (comme Apache ou Nginx)
- Composer (pour la gestion des dépendances PHP, si nécessaire)

## Installation

### Étape 1 : Téléchargement des fichiers

Téléchargez tous les fichiers du projet et placez-les dans un répertoire de votre serveur web.


### Étape 2 : Configuration de la base de données

Créez une base de données MySQL et un utilisateur avec tous les privilèges sur cette base de données.

```sql
CREATE DATABASE chatbot_db;
CREATE USER 'chatbot_user'@'localhost' IDENTIFIED BY 'motdepasse';
GRANT ALL PRIVILEGES ON chatbot_db.* TO 'chatbot_user'@'localhost';
FLUSH PRIVILEGES;
```

Importez le fichier `schema.sql` pour créer les tables nécessaires :

```bash
mysql -u chatbot_user -p chatbot_db < schema.sql
```

### Étape 3 : Configuration du projet

Créez un fichier `DataBaseConfig.php` avec les informations de connexion à la base de données :

```php
<?php
class DataBaseConfig {
    public $servername;
    public $username;
    public $password;
    public $databasename;

    public function __construct() {
        $this->servername = 'localhost';
        $this->username = 'chatbot_user';
        $this->password = 'motdepasse';
        $this->databasename = 'chatbot_db';
    }
}
?>
```

### Étape 4 : Configuration des API

Assurez-vous d'avoir les clés API nécessaires pour OpenAI, Claude et HuggingFace. Modifiez les fichiers PHP pour inclure vos clés API.

### Étape 5 : Lancer le serveur

Lancez votre serveur web (Apache, Nginx, etc.) et assurez-vous qu'il pointe vers le répertoire de votre projet.

## Structure du Projet

- `index.php` : La page principale du chatbot, affiche les messages et permet d'envoyer de nouveaux messages.
- `login.php` : La page de connexion des utilisateurs.
- `logout.php` : La page de déconnexion des utilisateurs.
- `register.php` : La page d'inscription des utilisateurs.
- `chatbot_response.php` : Gère les requêtes de l'utilisateur et envoie les messages aux API d'IA pour obtenir des réponses.
- `DataBase.php` : Fichier contenant les fonctions de base de données.
- `DataBaseConfig.php` : Fichier de configuration pour les informations de connexion à la base de données.
- `styles.css` et `styles1.css` : Feuilles de style pour le design du chatbot.
- `chat.php` : Fichier gérant la logique de chat côté serveur.
- `chatbot.png` et `send-message.png` : Images utilisées dans l'interface utilisateur.

## Utilisation

1. Accédez à `register.php` pour créer un nouveau compte utilisateur.
2. Connectez-vous via `login.php`.
3. Après la connexion, vous serez redirigé vers `index.php` où vous pourrez discuter avec le chatbot.
4. Tapez un message dans le champ de saisie et appuyez sur le bouton d'envoi.
5. Les messages sont affichés dans la zone de conversation et enregistrés dans la base de données.
6. Cliquez sur "Logout" pour vous déconnecter.

## Fichiers et Explications

### `index.php`

Affiche l'interface du chatbot et charge les messages précédents de l'utilisateur connecté.

### `login.php`

Page de connexion pour les utilisateurs. Vérifie les informations d'identification et démarre une session.

### `logout.php`

Déconnecte l'utilisateur en détruisant la session.

### `register.php`

Permet aux nouveaux utilisateurs de s'inscrire.

### `chatbot_response.php`

Gère les requêtes envoyées par l'utilisateur, envoie les messages aux API d'IA, reçoit les réponses, et enregistre les messages et les réponses dans la base de données.

### `DataBase.php`

Contient les fonctions de gestion de la base de données.

### `DataBaseConfig.php`

Fournit les informations de connexion à la base de données.

### `styles.css` et `styles1.css`

Fournissent le style pour l'interface utilisateur.

### `chat.php`

Gère la logique du chat côté serveur.

## Captures d'écran

- [Capture d'écran de la page de chat](https://ibb.co/D5CH5sC)
- [Capture d'écran de la page de connexion](https://ibb.co/sChKd5g)

## Auteur

Ce projet a été développé par asmaa el hint.
