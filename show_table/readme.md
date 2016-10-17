# show_table

Sometimes you've the need to extract informations from your Joomla's database like f.i. the list of users, articles, ...

Recently my need was to extract quickly the list of my customers i.e. 
* Personal informations like LastName, FirstName, Address, City, Country, ...
* Company information like the name of the customer's firm, his VAT number, ...
* The bought product (which version, with or without support), ...
* The paid price (netto, gross, paid VAT, currency, ...)

The need was also to make that list available in my spreadsheet software : create a worksheet with, as Data Source, a dynamic table.
By refreshing the worksheet, the spreadsheet software run the URL, get a newer version of the list and update the sheet.  
And the magic is there.

This script, show_table.php, allow this.

## Use it
To make this script yours : 

1. Download and copy the script on your Joomla website.   Put the script in the root folder of the site or in a subfolder.
2. Edit the script and change the SQL : write your own.  If the script has been stored in an another folder than the Joomla root folder, update the value of the constant
3. If you wish, change the password : get a newer md5 hash.
4. Save the file

## Run it
Start a browser and run the file.

You'll need to provide the password.  By default, it's Joomla so use an URL like this one :
http:// youriste/show_table.php?password=Joomla

### To get a RAW output 
http:// youriste/show_table.php?password=Joomla&format=RAW

### To get a HTML output 
http:// youriste/show_table.php?password=Joomla&format=HTML

<img src="https://github.com/cavo789/joomla_free/blob/master/show_table/sample.png" />
