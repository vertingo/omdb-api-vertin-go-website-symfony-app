Projet Front-Back End + Api Omdbapi
======================================================================================================
L'idée serait de gérer une plateforme permettant aux utilisateurs de se créer une liste de films favoris à partir d'une barre de recherche mais également de pouvoir en visualiser une bande annonce, donner un avis, et exporter au format pdf cette liste!


 

<center>Front-End</center>
<p align="center">
<a href="https://www.youtube.com/channel/UC2g_-ipVjit6ZlACPWG4JvA?sub_confirmation=1"><img src="https://raw.githubusercontent.com/vertingo/Omdb_Api_Vertin_Go_Website/master/images/Omdb_Api_Vertin_Go_Website4.png" width="1000" height="300"/></a>
<br>
<a href="https://www.youtube.com/channel/UC2g_-ipVjit6ZlACPWG4JvA?sub_confirmation=1"><img src="https://raw.githubusercontent.com/vertingo/Omdb_Api_Vertin_Go_Website/master/images/Omdb_Api_Vertin_Go_Website.png" width="1000" height="400"/></a>
</p>
<br>
<p align="center">
<a href="https://www.youtube.com/channel/UC2g_-ipVjit6ZlACPWG4JvA?sub_confirmation=1"><img src="https://raw.githubusercontent.com/vertingo/Omdb_Api_Vertin_Go_Website/master/images/Omdb_Api_Vertin_Go_Website2.png" width="400" height="250"/></a>
<a href="https://www.youtube.com/channel/UC2g_-ipVjit6ZlACPWG4JvA?sub_confirmation=1"><img src="https://raw.githubusercontent.com/vertingo/Omdb_Api_Vertin_Go_Website/master/images/Omdb_Api_Vertin_Go_Website3.png" width="400" height="250"/></a>
</p>
<br>
<center>Back-End</center>
<p align="center">
<a href="https://www.youtube.com/channel/UC2g_-ipVjit6ZlACPWG4JvA?sub_confirmation=1"><img src="https://raw.githubusercontent.com/vertingo/Omdb_Api_Vertin_Go_Website/master/images/Omdb_Api_Vertin_Go_Website5.png" width="1000" height="300"/></a>
<br>
<a href="https://www.youtube.com/channel/UC2g_-ipVjit6ZlACPWG4JvA?sub_confirmation=1"><img src="https://raw.githubusercontent.com/vertingo/Omdb_Api_Vertin_Go_Website/master/images/Omdb_Api_Vertin_Go_Website6.png" width="1000" height="400"/></a>
</p>

Prérequis avant de taper les lignes de commandes suivantes:
Placer le contenu du projet dans le dossier htdocs d'un simulateur de server local tel que xampp!
Veillez à ce que les champs de la base de donnée du fichier app/config/parameters soient renseignés
soit le port par defaut pour mysql 3306, le nom de la base de données qui doit au préalable être créée dans PhpMyAdmin!
Pour cela dans xampp lancer le server Apache, le server Mysql et connectez-vous à l'interface PhpMyAdmin en cliquant sur le bouton admin sur la ligne du Module MySql!
Ensuite cliquer sur nouvelle base de données et créer la base omdbapi!

Pour lancer le projet il suffit simplement de taper les commandes suivantes:

```
composer install

php bin/console doctrine:database:create (Ou créer la base de donnée omdbapi directement dans phpmyadmin)

php bin/console doctrine:schema:create  (Création des tables dans la base de donnée omdbapi)

php bin/console fos:user:create name test@example.com password  (Utilisation du bundle fosuserbundle)

php bin/console server:run

```
Accéder ensuite à la page de login:
==> http://localhost/Omdb_Api_Vertin_Go_Website/web/app_dev.php/login

Pour importer une liste de films via un fichier csv il faut créer un fichier csv et écrire sur chaque ligne add:le_nom_de_votre_film (Vous pouvez trouver un exemple de fichier csv à la racine du projet: csv_omdbapi.csv)

Partie Back-end

```
php bin/console fos:user:promote user ROLE_ADMIN 

(Pour donner les droits d'accès à un utilisateur au back-end qui sera accessible à cet url: 
==> http://localhost/Omdb_Api_Vertin_Go_Website/web/app_dev.php/admin 
aprés s'être réauthentifier sur la page de login: 
==> http://localhost/Omdb_Api_Vertin_Go_Website/web/app_dev.php/login)

```

================================================================================

Pour générer un crud sur une entité avec le Bundle Pektopara en 3 lignes de commandes:

```
php bin/console doctrine:generate:entity

php bin/console doctrine:schema:update --force

php bin/console generate:doctrine:crud

```

================================================================================
<p align="center">
  <a href="https://www.youtube.com/channel/UC2g_-ipVjit6ZlACPWG4JvA?sub_confirmation=1"><img src="http://vertin-go.com/Fonctions_Annexes/annexes/pdt-page-de-telechargement/Android%20You%20Tube%20Data%20API/youtube2.png" width="400" height="250"/></a>
  <a href="https://www.facebook.com/vertingo/"><img src="http://vertin-go.com/Fonctions_Annexes/annexes/pdt-page-de-telechargement/Android%20You%20Tube%20Data%20API/rejoins_nous.png" width="400" height="250"/></a>
</p>



