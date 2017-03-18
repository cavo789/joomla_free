# kill_db_tables

You've installed a lot of extensions to your site and there are tables in your database that are no more needed; not removed when you've uninstalled extensions.

You can also have old tables like the ones you've created by adding a new website to the same database (with an another prefix).

Or other scenarios.

So, time to time, it's nice to clean the database by removing such unused tables.

All these questions will find an answer with the `kill_db_tables.php` script.  

## Use it

Just copy the kill_db_tables.php script in the root folder of your Joomla's website.

1.  Get a raw version of the script : click on the raw button or go to this URL : [https://raw.githubusercontent.com/cavo789/joomla_free/master/src/kill_db_tables/kill_db_tables.php](https://raw.githubusercontent.com/cavo789/joomla_free/master/src/kill_db_tables/kill_db_tables.php)
2.  On your computer, start a text editor like Notepad or Notepad++ and copy/paste there the code
3.  Save the file (if you're using Notepad++, check in the Encoding menu that you've selected UTF8 NoBom as encoding)
4.  Put the saved file in the root folder of your Joomla's website


## Run it

Start a browser and run the file i.e go to f.i. [http://site/kill_db_tables.php](http://site/kill_db_tables.php).

The script will start immediatly and will display a form with buttons.  The script will start an ajax request for retrieving the list of tables of your database (the one referenced in the `configuration.php` file).

Then follow the instructions displayed on the screen.

## Remark

Don't forget to remove the script once you've finished with it.

## Images

<img src="https://github.com/cavo789/joomla_free/blob/master/src/kill_db_tables/result.png" />

## Credits

Christophe Avonture | [https://www.aesecure.com](https://www.aesecure.com)

-----

[Get other free scripts](https://github.com/cavo789/joomla_free)
