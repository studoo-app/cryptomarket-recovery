![separe](https://github.com/studoo-app/.github/blob/main/profile/studoo-banner-logo.png)
# CryptoMarket Audit et actions de maintenance curative
[![Version](https://img.shields.io/badge/Version-1.0.2-blue)]()

## Contexte
Cryptomarket une entreprise qui a développé une application de suivi de portefeuille de cryptomonnaies. 
Cette application permet de suivre l'évolution des cours des cryptomonnaies et de visualiser la valeur d'un portefeuille de cryptomonnaies.

A la suite d'une attaque de type ransomware relativement basique, le site a été corrompu et ne fonctionne plus.

Vous avez été embauché par l'entreprise pour réparer le site et le remettre en ligne. 
Pour ce faire, vous disposez du code source de l'application, d'une fraction de la base de production 
ainsi que d'un environnement de développement dockerisé.

Pour vous faciliter la navigation dans l'application, vous disposez aussi de 2 comptes utilisateurs :
- admin@cryptomarket.dev / admin
- user@cryptomarket.Dev / user

## Objectifs

- Réparer les dommages de l'attaque ransomware et rétablir le site
- Vous en profiterez pour auditer le code et appliquerez les modifications necéssaires pour le sécuriser
- Vous rédigerez un compte rendu sous format "cr-prenom-nom.md" où vous décrirez:
  - La nature de la faille relevée (définition technique)
  - les dommages potentiels
  - les mesures correctives et/ou curatives que vous avez apportées

## Installation
### Création du fichier d'environnement
```bash
    cp .env.example .env
```

### Démarrage des conteneurs docker
```bash
    docker-compose up -d
```

### Installation des dépendances
```bash
    docker exec -it cryptomarket-app composer install
```

### Migration de la base de données
```bash
    docker exec -it cryptomarket-app php bin/console d:m:m
```

### Déploiement des fixtures
```bash
  docker exec -it cryptomarket-app php bin/console d:f:l
```