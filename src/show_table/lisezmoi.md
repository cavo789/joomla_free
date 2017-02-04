# show_table.php - Afficher des données de Joomla!® en dehors de Joomla

*Cet article s'adresse à un public de programmeurs / utilisateurs avertis connaissant le langage SQL.  Si vous êtes en outre programmeur Excel / VBA, cet article est définitivement fait pour vous simplifier la vie.*

## Introduction

Récemment, j'ai pris un peu de temps pour améliorer ma gestion de clients : en tant que vendeur d'un logiciel et de prestations, et cela au travers d'un site Joomla, je tiens à jour un fichier Excel que j'utilise pour faire une fusion (un publipostage) avec Winword pour p.ex. générer des factures, des rapports d'activités, ... et génération de mails.

Dans ce fichier Excel, je note le pseudo de mon client, son adresse postale, son adresse email, son numéro de TVA s'il en a un, etc. ce qui était du double travail puisque, au moment de l'achat, l'acheteur avait déjà introduit toutes ces données dans sa fiche utilisateur sur mon site Joomla!®.

En bon informaticien toujours soucieux de réduire son travail manuel, mon besoin était donc : "**Depuis Excel, pouvoir lancer une requête vers mon site web pour en extraire la liste des utilisateurs et ainsi ne plus avoir à recopié les données déjà introduites par mes utilisateurs**".   Le script *show_table.php* est né à ce moment.

<img src="https://github.com/cavo789/joomla_free/blob/master/src/show_table/sample.png" width="680"/>

## show_table.php

Il s'agit d'un script `PHP` qui permet de lancer `une requête SQL` de votre choix (*=à vous de la programmer*) pour afficher au travers d'un `SELECT` les informations que vous voudriez voir apparaître sur une page.

Par exemple : 

* La liste de vos utilisateurs et leur appartenance aux groupes (enregistré, admininistrateur, ...),
* la liste des articles de votre site (trié par date de rédaction) avec mention de la catégorie à laquelle ils sont rattachés et mention des tags, 
* la liste des produits de votre site d'e-commerce avec le nombre d'articles encore en stock, 
* la liste des photos mentionnées dans votre composant de gestion d'albums,
* etc.

Bref : tout qui est enregistré sous forme d'une ou plusieurs tables liées dans la base de données de votre site Jooma!®.

`show_table.php` permet d'afficher un tel contenu et cela soit au format brut (*format=RAW*) càd une table non formatée (un simple tag `<table>`, ses colonnes et ses lignes) ou une table mise en page avec un affichage `Bootstrap` et du `jQuery` pour permettre de filtrer la table et de trier les colonnes (*format=HTML*).

L'affichage brut est celui qui sera utilisé comme source de données pour Excel c'est-à-dire celui que l'on va lier à une feuille de calcul Excel pour nous afficher, dans Excel, le contenu de notre table.  *On pourrait faire de même avec MS Access p.ex. et le concept des tables liées.*

L'affichage `Bootstrap`/ `jQuery` n'aura pour d'autre but que de consulter la table depuis une page web.

## Sécurité d'accès à l'information

Bien évidemment, ce script se doit d'être protégé dès lors que les données affichées sont sensibles.  C'est le cas pour votre liste d'utilisateurs, de votre stock, ...  Pour cette raison, le script a été développé afin de vérifier qu'un mot de passe (*à vous de le paramètrer*) soit bien renseigné dans l'URL.  Si ce n'est pas le cas, un formulaire de login sera affiché.  Nous y reviendrons ci-dessous.

## Utilisation du script

Téléchargez le script php depuis la page https://raw.githubusercontent.com/cavo789/joomla_free/master/src/show_table/show_table.php

Sauvez le fichier `show_table.php` à la racine de votre site web.  *Idéalemment travaillez uniquement en local*.

Avec un éditeur de texte (du type [Notepad++](https://notepad-plus-plus.org/fr/) (pour Windows), gedit ou [scite](https://doc.ubuntu-fr.org/scite) pour Ubuntu), éditez le fichier show_table.php

Il y aura deux choses que vous aurez à modifier : l'instruction SQL que vous remplacerez par la vôtre (votre `SQL ... FROM ... (INNER|LEFT|RIGHT) JOIN ... WHERE`) et le mot de passe pour l'accès aux données.

### Instruction SQL 

Si vous lisez encore cet article, si je ne vous ai pas perdu, nul doute que vous saurez ce que fais ci-dessous :

```SQL
SELECT U.id UserID, U.name Name, U.username UserName, U.email eMail, U.registerDate RegisterDate, U.lastvisitDate LastVisitDate, G.title GroupTitle 
FROM `#__users` U
   LEFT JOIN (`#__user_usergroup_map` as UG) ON UG.user_id=U.id
   LEFT JOIN (`#__usergroups` as G) on UG.group_id=G.id
ORDER BY registerDate DESC, name, GroupTitle ASC
```

*(affichage de la liste des utilisateurs (ID, pseudo, nom, email, ...), date de création, de dernière connexion et des groupes auxquels ils sont rattachés)*

Un autre exemple : 

```SQL
SELECT C.id As Article_ID, C.title As Article_Title, G.title As Category_Title, U.name As Author_Name, C.Hits As Hits, C.language As Language, C.created As Writen_Date 
FROM `#__content` C 
   LEFT JOIN `#__categories` G ON C.catid = G.id
   LEFT JOIN `#__users` U on C.created_by=U.id
WHERE (state=1)
ORDER BY C.created DESC
```

*(affichage de la liste des articles, la catégorie liée, nom de l'auteur, nombre de lecture, ...)*

Notez que même si nous sommes bien en dehors de Joomla!® les instructions SQL doivent respecter la norme `#_` dans le nom de la table.  Ce préfixe étant remplacé, par Joomla, par le préfixe de tables sur le site où `show_table.php` sera copié. 

**A vous de jouer : codez votre propre instruction SQL.**  Utilisez phpMyAdmin ou tout autre outil qui vous permettra d'obtenir le résultat que vous désirez.   Une fois en possession de votre instruction, copiez-en le code SQL dans la fenêtre d'édition de show_table.php.  Cette instruction sera forcément un `SELECT ... FROM ...` avec une clause WHERE (ou pas) et un ORDER BY (ou pas).

### Mot de passe

Par défaut, l'accès à la page est protégé par un mot de passe.  Le mot de passe par défaut est **Joomla**.

Le mot de passe est défini dans le code source de `show_table.php`, cherchez la ligne suivante (elle se trouve dans la partie supérieure du fichier) :

```php
define('PASSWORD','57ac91865e5064f231cf620988223590');
```

Le mot de passe, **Joomla**, est crypté en md5.  Utilisez un site internet comme p.ex. http://www.md5.cz/ pour obtenir le md5 d'un nouveau mot de passe (pour illustration le mot *show_table*, en md5, donne *1bb2132da14d711ab17d4786fcd80710*).  Copiez/coller votre hash md5 dans le fichier.

## Utilisation

Une fois votre instruction SQL et votre mot de passe défini et sauvez dans le fichier, vous avez donc un fichier `show_table.php` sur mesure et se trouvant dans le dossier racine de votre site web.

### Au départ de votre navigateur

Pour l'utiliser, rien de plus simple bien sûr : il suffit d'y accéder au départ de votre navigateur en vous rendant à l'URL php ![http://votre_site/show_table.php](). Après avoir complété le formulaire et introduit votre mot de passe, si vous n'avez pas commis d'erreur, vous verrez apparaître vos données sous un format tableau (avec mise en page Bootstrap et fonctionnalités de tri / filtrages grâce à jQuery).

Si vous voyez des erreurs, retournez dans votre éditeur et corriger votre instruction SQL.

Une fois votre instruction SQL parachevée, le script `show_table.php` est fin prêt.  Soit vous le laissez "tel quel" càd que votre besoin a trouvé réponse; vous souhaitiez afficher rapidement des informations extraites de votre site et cela dans une page web simple dont l'accès est protégé.   C'est donc chose faite.  Bravo.  Soit, au contraire, vous désirez aller une étape plus loin et automatiser l'obtention de ces données dans votre tableur.

### Au départ d'Excel

*Peut-être est-ce aussi possible avec d'autres tableurs mais désolé, je n'utilise qu'Excel.* 

*Mise-à-jour 19/12 - Emmanuel Danan (@vistamedia) m'indique qu'il serait possible de récupérer des données externes depuis Open Office.  Lire [https://wiki.openoffice.org/wiki/Documentation/OOo3_User_Guides/Calc_Guide/Linking_to_external_data](https://wiki.openoffice.org/wiki/Documentation/OOo3_User_Guides/Calc_Guide/Linking_to_external_data)*



**Depuis Microsoft Excel, créez un nouveau fichier ou ouvrez un fichier existant (votre fichier de gestion de clients p.ex.).

Activez une feuille de calcul vierge et cliquez sur le menu Données (*Data*).  Cliquez ensuite sur le bouton "Depuis le web" (*From Web*).

Une nouvelle fenêtre va s'afficher et vous devrez renseigner l'adresse vers une page web.  Il faut y mentionner l'URL vers le script `show_table.php` avec, attention, deux paramètres : le mot de passe à utiliser et le format RAW, lire ci-dessous. 

<img src="https://github.com/cavo789/joomla_free/blob/master/src/show_table/worksheet.png" width="680" />

En Excel, l'affichage sera fera dans un tableau sans mise en forme aucune mais rien ne vous interdit ensuite de mettre en forme la ligne avec les noms des champs, d'insérer des lignes vierges au-dessus pour afficher un titre, ...

Et la magie opérant : au départ d'Excel, il suffit de cliquer sur le tableau ainsi générer et dans le ruban / la barre d'outils d'Excel vous pourrez mettre à jour vos données sans même faire quoi que ce soit en Joomla.  Un "Rafraîchir" va relancer la requête vers votre site Joomla!, extraire et récupérer les nouvelles données et les afficher dans Excel. Et si vous êtes programmeur VBA, vous pourrez encore davantage automatiser cela, il suffira de faire un "refresh" en programmation p.ex. lors de l'ouverture du fichier.

## Paramètres en querystring

A l'utilisation de `show_table.php, vous verrez que des paramètres en querystring sont utilisés :

### password

Le mot de passe, **<u>en clair!</u>**, est mentionné dans l'URL.  Lorsque le paramètre `password` est précisé, le script ne va plus afficher le formulaire de connexion (si le mot de passe est valide) et immédiatement afficher les résultats.

### format

Ce paramètre peut contenir une des deux valeurs suivantes :

*  RAW : pour forcer un affichage brut des données (càd aucune mise en page, pas de Bootstrap ni de jQuery).  **Ce mode doit être utilisé si vous souhaitez récupérer vos données dans un tableur**.
*  HTML : mode par défaut, affichage du résultat pour qu'il soit agréable depuis un navigateur.


Le script `show_table.php` étant libre de droit et proposé en Open Source, n'hésitez pas à ajouter vos propres paramètres pour p.ex. faire des filtres (country pour limiter l'affichage aux utilisateurs d'un pays, period pour limiter l'affichage des ventes pour un trimestre, year pour les articles écris durant une année précise, author pour les articles d'un auteur particulier, etc.)

## Crédits

`show_table.php` est un script conçu par Christophe Avonture, développeur d'[aeSecure](https://www.aesecure.com/fr/)

-----

[Get other free scripts](https://github.com/cavo789/joomla_free)