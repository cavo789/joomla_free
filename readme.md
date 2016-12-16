# Some scripts and snippets to simplify your tasks Joomla #

In this repository, you'll find a few scripts that I've written for my own use.  Perhaps one of these scripts will be usefull for you too.

## Scripts ##

### check_db ###

> Quickly check if your database is up and running and obtain the list of tables (and number of records in each of them.

`check_db.php` will retrieve the database's configuration from your Joomla's `configuration.php` file and will establish a connection with your server.  In case of failure, you'll obtain technical informations about the encountered error. 

[go to check_db](https://github.com/cavo789/joomla_free/tree/master/check_db)

### dump_db ###

> Want a very easy way to take a dump of your Joomla's database ? Don't want to enter to your admin or hosting cpanel ?  Perhaps you don't have such access (just FTP and nothing else).

The `dump_db.php` script will generate an extract of your database and immediatly send it to the browser (there is no file generated on the server).

[go to dump_db](https://github.com/cavo789/joomla_free/tree/master/dump_db)

### folder_size ###

> Do you know the size of your website ?  How many megabytes/gigabytes ? 

Searching for a tool that will display the disk size taken by your website and display the results : 

1. By folder (*which ones is the biggest consumer ?*)
2. By file's extensions (*what's the size of all pdfs, images, ...? ?*)

All these questions and more will find an answer with the `folder_size.php` script.

[go to folder_size](https://github.com/cavo789/joomla_free/tree/master/folder_size)

### kill_folder ###

> Very fast way to remove every files in a folder (subfolders included)

`kill_folder.php` will allow to quickly remove a folder structure i.e. including subfolders by running the script from an URL.  This way is MUCH faster than killing a folder from a FTP client which is awfully slow.

[go to kill_folder](https://github.com/cavo789/joomla_free/tree/master/kill_folder)

### log_admin ###

> You've forget your admin login or admin password ?  Use `log_admin.php` and connect to your backend

`log_admin.php` is a script proposed by **Yann Gomiero** on the French Joomla.fr forum.  This script is really straigt-forward and easy : put the script in the `/administrator` folder and run it.  It's done, you're connected on your administrator.   

[go to log_admin](https://github.com/cavo789/joomla_free/tree/master/log_admin)

### show_table ###

> Extract informations from your Joomla database and display them in a raw table; allowing to quickly connect that table into a spreadsheet program (like MS Excel)

`show_table.php` allows you to extract informations from your Joomla database : run a SQL query and export it as a table.  Nice HTML output with Bootstrap and jQuery or a RAW output to only output a `<table>` tag so the generated table can be easily reuse in, for instance, a spreadsheet program.

[go to show_table](https://github.com/cavo789/joomla_free/tree/master/show_table)

### zip ###

> Take a backup of your website or any folder, quickly.

By putting the `zip.php` script in any of your folder, you'll get an archive of that folder (subfolders included).  By putting the script in your website's rootfolder you'll then get a backup (of files) of your website.  No more difficult than that. 

[go to zip](https://github.com/cavo789/joomla_free/tree/master/zip)

## Snippets ##

### ftp_get ###

Small implementation of a FTP connection to retrieve and download a file from an another server.   You can use this file to, for instance, download a big file from one FTP to an another without, first, download the file on your computer.

[go to ftp_get](https://github.com/cavo789/joomla_free/tree/master/ftp_get)

## Credits ##

Christophe Avonture | [https://www.aesecure.com](https://www.aesecure.com)