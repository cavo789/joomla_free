# Some scripts and snippets to simplify your tasks Joomla

In this repository, you'll find a few scripts that I've written for my own use. Perhaps one of these scripts will be useful for you too.

## Scripts

### album_photo

> Display a nice bootstrap images carousel on your Joomla website. This script is straightforward : put images in a folder of your website and add just one line in your Joomla's article to get the carousel.

Easy way to display a carousel on any Joomla website. Use Bootstrap, ReReplacer and Sourcerer of Register Labs.

[go to album_photo](https://github.com/cavo789/joomla_free/tree/master/src/album_photo)

### check_db

> Quickly check if your database is up and running and obtain the list of tables (and number of records in each of them.

`check_db.php` will retrieve the database's configuration from your Joomla's `configuration.php` file and will establish a connection with your server. In case of failure, you'll obtain technical informations about the encountered error.

[go to check_db](https://github.com/cavo789/joomla_free/tree/master/src/check_db)

### dump_db

> Want a very easy way to take a dump of your Joomla's database ? Don't want to enter to your admin or hosting cpanel ? Perhaps you don't have such access (just FTP and nothing else).

The `dump_db.php` script will generate an extract of your database and immediatly send it to the browser (there is no file generated on the server).

[go to dump_db](https://github.com/cavo789/joomla_free/tree/master/src/dump_db)

### kill_db_tables

> Want a fast way to clean your database ?

`kill_db_tables.php` will display the list of tables found in your database and, thanks to a filtering, you'll define a pattern like f.i. "backup" for matching every tables with that word in their names. Then, after confirmation, the script will remove these tables from your db.

[go to kill_db_tables](https://github.com/cavo789/joomla_free/tree/master/src/kill_db_tables)

## Snippets

### ftp_get

Small implementation of a FTP connection to retrieve and download a file from an another server. You can use this file to, for instance, download a big file from one FTP to an another without, first, download the file on your computer.

[go to ftp_get](https://github.com/cavo789/joomla_free/tree/master/src/ftp_get)

## Credits

Christophe Avonture | [https://www.avonture.be](https://www.avonture.be)
