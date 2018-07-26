# log_admin - Log to your Joomla administrator without credentials

[English version](#english-version) - [Version francophone](#version-francophone)

## English version

> You don't have (anymore) your Joomla admin login and/or password (or just don't have one) but you've well access to your FTP

### Description

When you don't remember again your super admin account or even password, `log_admin` will allow you to start your administrator interface by, just, putting this script in your `/administrator` folder.

Don't be blocked anymore or don't play anymore with `phpMyAdmin` for creating a new admin / resetting the password / ..., just use this straigth-forward script.

#### Note

The "official" (= recommended by the Joomla project) way is either to edit the `configuration.php` file or to go `phpMyAdmin` and create a new user / reset an existing user. More info on [https://docs.joomla.org/How_do_you_recover_or_reset_your_admin_password%3F](https://docs.joomla.org/How_do_you_recover_or_reset_your_admin_password%3F)

#### BE CAREFULL !!!

**If you've copy this script onto your website, don't forget it ! The script will be automatically deleted after the first use but ... use it ;-) If the script stay unused, don't forget to delete it.**

### Install

1.  Make a right-clic on the hyperlink to [log_admin.php](https://raw.githubusercontent.com/cavo789/joomla_free/master/src/log_admin/log_admin.php) and select `save the target of the link as` so you can save the file on your hard disk.
2.  With a FTP client, copy the downloaded file onto your website, in the `/administrator` folder.

### Usage

Start a browser and run the script by going to `https://yourwebsite/administrator/log_admin.php`.

The script will start, retrieve the first non blocked super-admin, start a session with that user, open your Joomla backend interface and make a suicide: the script will be removed automatically.

Now, that you're in the backend, you can do what you want like:

- Reset the super admin login / password or create a new one,
- Check authentication plugins to make sure that the Joomla native authentication plugin is well enabled,
- Disable for instance the `Google Two Factors Authentication`,
- ...

### Other ressources

- [Tutorial: The most popular ways to recover your lost Joomla password, by Joomshaper](https://www.joomshaper.com/blog/the-most-popular-ways-to-recover-your-lost-joomla-password)

### Image

<img src="https://github.com/cavo789/joomla_free/blob/master/src/log_admin/result.png" />

### Credits

Yann Gomiero (aka daneel) and various contributors

### License

[MIT](LICENSE)

[Get other free scripts](https://github.com/cavo789/joomla_free)

---

## Version francophone

> Vous n'êtes plus en possession de votre login et/ou mot de passe administrateur Joomla (ou vous n'en avez pas) mais, par contre, vous avez encore accès à votre interface FTP.

### Description

Si vous ne vous souvenez plus de votre compte super admin ou même de votre mot de passe, `log_admin` vous permettra d'accéder à votre interface administrateur en plaçant simplement ce script dans votre dossier `/administrator`.

Ne soyez plus bloqué ou ne jouez plus avec `phpMyAdmin` pour créer un nouvel admin / réinitialiser le mot de passe / ...

##### Remarque

La manière "officielle" (= recommandée par le projet Joomla) est soit d'éditer le fichier `configuration.php` soit d'utiliser `phpMyAdmin` et de créer un nouvel utilisateur / réinitialiser un utilisateur existant. Plus d'infos sur [https://docs.joomla.org/How_do_you_recover_or_reset_your_admin_password%3F/fr](https://docs.joomla.org/How_do_you_recover_or_reset_your_admin_password%3F/fr)

#### FAITES ATTENTION!!!

Si vous avez copié ce script sur votre site, ne l'oubliez pas! Le script sera automatiquement supprimé après la première utilisation mais... faut-il encore l'utiliser ;-) Si le script n'est pas exécuté, n'oubliez pas de le supprimer.\*\*

### Installation

1.  Faites un clic-droit sur le lien vers le fichier [log_admin.php](https://raw.githubusercontent.com/cavo789/joomla_free/master/src/log_admin/log_admin.php) et sélectionnez `enregistrer la cible du lien sous` afin de sauver le fichier sur votre ordinateur.
2.  Avec un client FTP, copiez le fichier que vous venez de télécharger dans le dossier `/administrator` de votre site Joomla.

### Utilisation

Démarrez un navigateur et lancez le script en allant à `https://yourwebsite/administrator/log_admin.php`.

Le script démarrera, récupérera le premier super-admin non bloqué, lancera une session avec cet utilisateur, ouvrira votre interface Joomla backend et fera un suicide : le script sera supprimé automatiquement.

Maintenant que vous êtes dans le back-end, vous pouvez faire ce que vous voulez :

- Réinitialisez le login / mot de passe du super admin ou créez-en un nouveau,
- Vérifiez les plugins d'authentification pour vous assurer que le plugin d'authentification natif de Joomla est bien activé,
- Désactivez par exemple l'authentification `Google Two Factors Authentication`,
- ...

### Image

<img src="https://github.com/cavo789/joomla_free/blob/master/src/log_admin/result.png" />

### Crédits

Yann Gomiero (alias daneel) et de nombreux contributeurs

### Licence

[MIT](LICENSE)

[Obtenir d'autres scripts gratuits](https://github.com/cavo789/joomla_free)
