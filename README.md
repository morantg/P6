# P6
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/9dc27ac0d6104c979e9677aca9b05ec1)](https://www.codacy.com/manual/morantg/P6?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=morantg/P6&amp;utm_campaign=Badge_Grade)

# Pré-requis

- Composer doit être installé sur votre ordinateur

- Vous devez posséder une version de PHP 7.1 minimum.
Vous pouvez le vérifiez avec la commande php -v dans votre terminal.



# Installation

## Etape 1

- Placez vous dans le dossier ou vous souhaitez importer le projet et taper la commande 
de création de projet symfony :
> composer create-project symfony/website-skeleton my_project_name

## Etape 2

- Configurez vos variables d'environnement dans le fichier .env (variable DATABASE_URL)

## Etape 3

- Créez la base de donné :
> php bin/console doctrine:database:create

## Etape 4

- Télécharger le fichier .zip du projet sur votre machine locale.
Ajoutez ces fichier dans votre projet en choisissant l'option copier et remplacer.

## Etape 5 

- Installer les librairy faker et knp Paginator avec les commandes suivante :
> composer require fzaninotto/faker

> composer require knplabs/knp-paginator-bundle 

## Etape 6    

- Réalisez la migration de votre base de donnée avec les commandes suivantes :
> php bin/console make:migration

> php bin/console doctrine:migrations:migrate

## Etape 7 (optionnel)    

- Vous pouvez tester l'application avec un jeu de données fictives.
Pour cela charger les fixtures avec la commande suivante : 
> php bin/console doctrine:fixtures:load

Vous pouvez maintenant tester l'application. Un utilisateur de test à
été créée :

- nom : test
- mail : test@gmail.com
- mot de passe : testtest
