# kill_folder

When you need to kill a full folder structure by using a FTP client you know that it's slow, really slow and by slow, I mean awfully slow.

The `kill_folder.php` script is the one I use when I need to work on an hacked site and when my client allow me to, f.i., kill an old website.

## BE CAREFULL !!!

**This script is dangerous**

**This script is REALLY dangerous**

Use it under your own responsability.  The aim of the script is to kill files and folders i.e. not to take an archive, move files to another location, ... just kill them.    

## Use it

Just copy the `kill_folder.php` script in the folder you want to remove.  So, if you want to kill the /www_root/old_site folder (and subfolders), use your FTP client and put `kill_folder.php` into the old_site root.

## .htaccess and index.html / security

To ensure the security of the folder where you put `kill_folder.php`, if that folder contains a `.htaccess` (for security reasons) or a `index.html` (to prevent to browse the folder's content), these files will remains.   So, running `kill_folder.php` will kill everything except these two files.

## Run it

Start a browser and run the file i.e go to f.i. [http://your_old_site/kill_folder.php](http://your_old_site/kill_folder.php).

Click on the Clean button, wait a few seconds and when it's done, click on the "Kill this script" button so the `kill_file.php` script will be removed from your website.

## Remark

To be clear enough : this script only remove files.  If your website used a database like any CMS, the database stay untouch.

## Images

<img src="https://github.com/cavo789/joomla_free/blob/master/src/kill_folder/result.png" />

## Credits

Christophe Avonture | [https://www.aesecure.com](https://www.aesecure.com)

-----

[Get other free scripts](https://github.com/cavo789/joomla_free)
