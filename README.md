# Locauto - Plateforme de Location Automobile

Ce projet est une plateforme de location automobile permettant aux utilisateurs de réserver des véhicules, aux propriétaires de mettre leurs véhicules en location, et aux administrateurs de gérer l’ensemble du parc automobile.

## Fonctionnalités

### Gestion des comptes
- Inscription et connexion sécurisée (`register.php`, `login.php`, `logout.php`)
- Différents rôles : Utilisateur / Admin

### Gestion des véhicules
- Liste des véhicules disponibles (`vehicle.php`)
- Ajout, modification et suppression de véhicules (interface Admin)

### Réservation
- Consultation des véhicules et réservation (`reserve.php`)
- Gestion du planning (`planing.php`)

### Administration
- Gestion des annonces (`admin_annonces.php`)
- Gestion des clients (`admin_clients.php`)

## Installation et utilisation

### Cloner le projet
```bash
git clone https://github.com/tonpseudo/Locauto.git
cd Locauto
```

### Configuration de la base de données
1. Importer le fichier `locauto (8).sql` dans MySQL (via phpMyAdmin ou autre outil)
2. Modifier la connexion à la base de données dans les fichiers PHP concernés (ex: `config.php`)

### Lancer le serveur local
Si vous utilisez XAMPP ou WAMP :
- Placer le dossier dans `htdocs/`
- Démarrer Apache et MySQL
- Accéder à `http://localhost/Locauto`

## Technologies utilisées
- PHP (Backend)
- MySQL (Base de données)
- HTML / CSS (Frontend)
- Apache (XAMPP / WAMP) pour l’exécution locale

Projet réalisé dans le cadre de la formation à Sup de Vinci.
