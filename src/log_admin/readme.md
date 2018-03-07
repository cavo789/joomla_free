# log_admin - Log to your Joomla administrator without credentials

[English version](#english-version) - [Version francophone](#version-francophone)

## English version

> You don't have (anymore) your Joomla admin login and/or password (or just don't have one) but you've well access to your FTP

### Description

When you don't remember again your super admin account or even password, `log_admin` will allow you to start your administrator interface by, just, putting this script in your `/administrator` folder.

Don't be blocked anymore or don't play anymore with `phpMyAdmin` for creating a new admin / resetting the password / ..., just use this straigth-forward script.

#### Note

The "official" (= recommended by the Joomla project) way is either to edit the `configuration.php` file or to go `phpMyAdmin` and create a new user / reset an existing user.  More info on [https://docs.joomla.org/How_do_you_recover_or_reset_your_admin_password%3F](https://docs.joomla.org/How_do_you_recover_or_reset_your_admin_password%3F)

#### BE CAREFULL !!!

**If you've copy this script onto your website, don't forget it ! The script will be automatically deleted after the first use but ... use it ;-)  If the script stay unused, don't forget to delete it.**

### Install

Download a copy of the `log_admin.php` script in your `/administrator` folder of Joomla.

#### Get the script

Two methods:

##### Method one

1. Get a raw version of the script: click on the `raw` button or go to this URL: [https://raw.githubusercontent.com/cavo789/joomla_free/master/src/log_admin/log_admin.php](https://raw.githubusercontent.com/cavo789/joomla_free/master/src/log_admin/log_admin.php)
2. On your computer, start a text editor like `Notepad` or `Notepad++` and copy/paste there the code
3. Save the file (if you're using `Notepad++`, check in the `Encoding` menu that you've selected `UTF-8` as encoding)
4. Put the saved file in your `/administrator` folder of your website (use your FTP client for this)

##### Method two

1. Go to [https://github.com/cavo789/joomla_free/](https://github.com/cavo789/joomla_free/) and click on the green button `Clone or download` and select `Download ZIP`.
2. Once download, open the zip file and retrieve the `log_admin.php` file (located in the `joomla_free-master` folder, then `src`, then `log_admin` subfolders)
3. Save the `log_admin.php` file to your disk.
4. Put the saved file in your /administrator folder of your website (use your FTP client for this)

### Usage

Start a browser and run the script by going to `https://yourwebsite/administrator/log_admin.php`.

The script will start, retrieve the first non blocked super-admin, start a session with that user, open your Joomla backend interface and make a suicide: the script will be removed automatically.

Now, that you're in the backend, you can do what you want like:

* Reset the super admin login / password or create a new one,
* Check authentication plugins to make sure that the Joomla native authentication plugin is well enabled,
* Disable for instance the `Google Two Factors Authentication`,
* ...

### Images

<img src="https://github.com/cavo789/joomla_free/blob/master/src/log_admin/result.png" />

### Credits

Yann Gomiero (aka daneel) and various contributors

### License

[MIT](LICENSE)

---

## Version francophone

> Vous n'êtes plus en possession de votre login et/ou mot de passe administrateur Joomla (ou vous n'en avez pas) mais, par contre, vous avez encore accès à votre interface FTP.

### Description

Si vous ne vous souvenez plus de votre compte super admin ou même de votre mot de passe, `log_admin` vous permettra d'accéder à votre interface administrateur en plaçant simplement ce script dans votre dossier `/administrator`.

Ne soyez plus bloqué ou ne jouez plus avec `phpMyAdmin` pour créer un nouvel admin / réinitialiser le mot de passe / ...

##### Remarque

La manière "officielle" (= recommandée par le projet Joomla) est soit d'éditer le fichier `configuration.php` soit d'utiliser `phpMyAdmin` et de créer un nouvel utilisateur / réinitialiser un utilisateur existant. Plus d'infos sur [https://docs.joomla.org/How_do_you_recover_or_reset_your_admin_password%3F/fr](https://docs.joomla.org/How_do_you_recover_or_reset_your_admin_password%3F/fr)

#### FAITES ATTENTION!!!

Si vous avez copié ce script sur votre site, ne l'oubliez pas! Le script sera automatiquement supprimé après la première utilisation mais... faut-il encore l'utiliser ;-) Si le script n'est pas exécuté, n'oubliez pas de le supprimer.**

### Installation

Téléchargez une copie du script `log_admin.php` dans votre dossier `/administrator` de Joomla.

#### Obtenir une copie du script

Vous pouvez obtenir le script `log_admin.php` de deux manières :

##### Première méthode

1. Obtenir une version dite `Raw` du script : cliquez sur le bouton `raw` ou allez à l'URL suivante : [https://raw.githubusercontent.com/cavo789/joomla_free/master/src/src/log_admin/log_admin.php](https://raw.githubusercontent.com/cavo789/joomla_free/master/src/log_admin/log_admin.php)
2. Sur votre ordinateur, lancez un éditeur de texte comme `Notepad` ou `Notepad++` et copiez/collez le code.
3. Sauvegardez le fichier (si vous utilisez `Notepad++`, vérifiez dans le menu `Encodage` que vous avez sélectionné `UTF-8` comme encodage)
4. Mettez le fichier sauvegardé dans le dossier `/administrator` de votre site web (utilisez pour cela votre client FTP)

##### Deuxième méthode

1. Allez sur [https://github.com/cavo789/joomla_free/](https://github.com/cavo789/joomla_free/) et cliquez sur le bouton vert `Clone or download` et sélectionnez `Download ZIP`.
2. Une fois le téléchargement terminé, ouvrez le fichier zip téléchargé et récupérez le fichier `log_admin.php` (situé dans le dossier `joomla_free-master`, puis `src`, puis `log_admin`)
3. Enregistrez le fichier `log_admin.php` sur votre disque.
4. Mettez le fichier sauvegardé dans le dossier `/administrator` de votre site web (utilisez pour cela votre client FTP)

### Utilisation

Démarrez un navigateur et lancez le script en allant à `https://yourwebsite/administrator/log_admin.php`.

Le script démarrera, récupérera le premier super-admin non bloqué, lancera une session avec cet utilisateur, ouvrira votre interface Joomla backend et fera un suicide : le script sera supprimé automatiquement.

Maintenant que vous êtes dans le back-end, vous pouvez faire ce que vous voulez :

* Réinitialisez le login / mot de passe du super admin ou créez-en un nouveau,
* Vérifiez les plugins d'authentification pour vous assurer que le plugin d'authentification natif de Joomla est bien activé,
* Désactivez par exemple l'authentification `Google Two Factors Authentication`,
* ...

### Images

<img src="https://github.com/cavo789/joomla_free/blob/master/src/log_admin/result.png" />

### Crédits

Yann Gomiero (alias daneel) et de nombreux contributeurs

-----

[Get other free scripts](https://github.com/cavo789/joomla_free)
