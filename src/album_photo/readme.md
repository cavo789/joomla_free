# album_photo

Display a nice bootstrap images carousel on your Joomla website. This script is straightforward : put images in a folder of your website and add just one line in your Joomla's article to get the carousel.

Online demo : http://jugwallonie.be/compte-rendu-20160521.html (at the end of the page)

## Use it

### Setup phase (to do only once)

1.  Create a new folder in your Joomla root folder (f.i. /scripts). _You can protect that folder by putting a .htaccess file in it with "deny from all" if you want._
2.  Copy there a raw version of the `album_photo.php`script :
    1.  Clic on the `raw` button to see the script without any HTML tags or use this URL : [https://raw.githubusercontent.com/cavo789/joomla_free/master/src/album_photo/album_photo.php](https://raw.githubusercontent.com/cavo789/joomla_free/master/src/album_photo/album_photo.php)
    2.  On your computer, start a text editor like Notepad or Notepad++ and copy/paste there the code
    3.  Save the file (if you're using Notepad++, check in the Encoding menu that you've selected UTF8 NoBom as encoding)
    4.  Put the saved file in your new folder
3.  Install the component [ReReplacer](https://www.regularlabs.com/extensions/rereplacer) of Register Labs. The Free version is enough. Once installed, make sure that the Regular Labs Joomla System plugin is enabled.
4.  Make the same for [Sourcerer](https://www.regularlabs.com/extensions/sourcerer) of the same editor. The free version is enough too. Verify that the Sourcerer system plugin is enabled too.
5.  Go to the Components tab and click on ReReplacer.
6.  Create a new rule
    1.  Type `\[cavo_photo (.*?)\]` for the search pattern
    2.  Type these lines. Be sure to correctly specify the name of your script.

```php
{source}

<?php

$folder='\1';

include JPATH_SITE.'/scripts/album_photo.php';

?>

{/source}
```

    3.  Set "Regular expressions" to True
    4.  And be sure to enable the rule

   <img src="https://github.com/cavo789/joomla_free/blob/master/src/album_photo/rereplacer.png" />

6.  Save that rule.

### For each carousel

1.  Create a folder like f.i. /images/blog/first
2.  Put your pictures there
3.  Edit a Joomla article and add this line `[cavo_photo images/blog/first]`
4.  Save the article and display it

If the setup phase was correctly done, now, you should see your carousel

## Run it

## Remark

## Images

<img src="https://github.com/cavo789/joomla_free/blob/master/src/album_photo/result.png" />

## Credits

Christophe Avonture | [https://www.avonture.be](https://www.avonture.be)

---

[Get other free scripts](https://github.com/cavo789/joomla_free)
