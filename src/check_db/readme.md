# check_db

Is your Joomla's database up and running ? The idea behind `check_db.php` is verify the connection and, if ok, display the list of tables found in your database (with same prefix than the configured one in `configuration.php`)

## Use it

Just copy the check_db.php script in the root folder of your Joomla's website.  Use your FTP client to do this.

1.  Get a raw version of the script : click on the raw button or go to this URL : https://raw.githubusercontent.com/cavo789/joomla_free/master/src/check_db/check_db.php
2.  On your computer, start a text editor like Notepad or Notepad++ and copy/paste there the code
3.  Save the file (if you're using Notepad++, check in the Encoding menu that you've selected UTF8 NoBom as encoding)
4.  Put the saved file in your Joomla's root folder

## Run it

Start a browser and run the file i.e go to f.i. [http://site/check_db.php](http://site/check_db.php).

The script will start immediatly, read your configuration, establish a connection to your database and display results : errors (in case of incorrect settings, server down, ...) or the list of tables and number of items in each of them.

## Remark

Don't forget to remove the script once you've finished with it.

## Images

<img src="https://github.com/cavo789/joomla_free/blob/master/src/check_db/result.png" />

## Credits

Christophe Avonture | [https://www.aesecure.com](https://www.aesecure.com)

-----

[Get other free scripts](https://github.com/cavo789/joomla_free)
