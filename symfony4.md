CREATE ENTITY
*************

>php bin/console make:entity
>category :nom categorie
>name     :champ 

php bin/console make:entity Category :Permet d'ajouter des champs à un entité existant

Migration à la bdd
------------------
>php bin/console make:migration : créer un fichier de migration 
>php bin/console doctrine:migrations:migrate  : Mettre à jours la bdd
>php bin:console doctrine:migrations:status : donne une édée sur l'évolution des migrations 
>php bin/console doctrine:migrations:migrate 20200129150628 : Permet de migrer vers une version spécique


CREATE CONTROLLER
*****************

>php bin/console make:controller Category   : créer le controller
>php bin/console make:crud   : créer tout les crud




SYMFONY 3
---------
Installation Symfony  3
--------------------------------------
composer create-project symfony/framework-standard-edition  project-name

Base de données
________________

- création Bdd
php bin/console doctrine:database:create

- création des tables
php bin/console doctrine:schema:create

-Afficher le modif 
php bin/console doctrine:schema:update --dump-sql

- mise à jours bdd
php bin/console doctrine:schema:update --force

- génération entité
php bin/console doctirne:generate:entity

-mettre à jours l'entité
php bin/console doctirne:generate:entities  OCPlatformBundle:Categorie

Création Bundle
_______________

php bin/console generate:bundle

creationn Crud
______________

php bin/console generate:doctrine:crud
