# Some scripts and snippets to simplify your tasks Joomla

In this repository, you'll find a few scripts that I've written for my own use. Perhaps one of these scripts will be usefull for you too.

## Scripts

### album_photo

> Display a nice bootstrap images carousel on your Joomla website. This script is straightforward : put images in a folder of your website and add just one line in your Joomla's article to get the carousel.

Easy way to display a carousel on any Joomla website. Use Bootstrap, ReReplacer and Sourcerer of Register Labs.

[go to album_photo](https://github.com/cavo789/joomla_free/tree/master/src/album_photo)

### check_db

> Quickly check if your database is up and running and obtain the list of tables (and number of records in each of them.

`check_db.php` will retrieve the database's configuration from your Joomla's `configuration.php` file and will establish a connection with your server. In case of failure, you'll obtain technical informations about the encountered error.

[go to check_db](https://github.com/cavo789/joomla_free/tree/master/src/check_db)

### chmod

> Recursive chmod, apply 755 and 644 for folders and files.

`chmod.php` will reset folder's permissions to 755 and 644 for files, this for the folder where the script is stored and any subfolders.

[go to check_db](https://github.com/cavo789/joomla_free/tree/master/src/chmod)

### dump_db

> Want a very easy way to take a dump of your Joomla's database ? Don't want to enter to your admin or hosting cpanel ? Perhaps you don't have such access (just FTP and nothing else).

The `dump_db.php` script will generate an extract of your database and immediatly send it to the browser (there is no file generated on the server).

[go to dump_db](https://github.com/cavo789/joomla_free/tree/master/src/dump_db)

### kill_db_tables

> Want a fast way to clean your database ?

`kill_db_tables.php` will display the list of tables found in your database and, thanks to a filtering, you'll define a pattern like f.i. "backup" for matching every tables with that word in their names. Then, after confirmation, the script will remove these tables from your db.

[go to kill_db_tables](https://github.com/cavo789/joomla_free/tree/master/src/kill_db_tables)

### php_grep

This script will allow you to scan files of your website and search for a specific pattern; f.i. a word or a sentence.

[go to php_grep](https://github.com/cavo789/joomla_free/tree/master/src/php_grep)

### zip

> Take a backup of your website or any folder, quickly.

By putting the `zip.php` script in any of your folder, you'll get an archive of that folder (subfolders included). By putting the script in your website's rootfolder you'll then get a backup (of files) of your website. No more difficult than that.

`unzip.php` will allow you to unzip any `.zip` file stored in the same folder of the script.

[go to zip](https://github.com/cavo789/joomla_free/tree/master/src/zip)

## Snippets

### ftp_get

Small implementation of a FTP connection to retrieve and download a file from an another server. You can use this file to, for instance, download a big file from one FTP to an another without, first, download the file on your computer.

[go to ftp_get](https://github.com/cavo789/joomla_free/tree/master/src/ftp_get)

## Credits

Christophe Avonture | [https://www.aesecure.com](https://www.aesecure.com)
