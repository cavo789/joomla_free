# album_photo

Display a nice bootstrap images carousel on your Joomla website.  This script is straightforward : put images in a folder of your website and add just one line in your Joomla's article to get the carousel.

Online demo : http://jugwallonie.be/compte-rendu-20160521.html (at the end of the page)

## Use it

### Setup phase (to do only once)
1. Create a new folder in your Joomla root folder (f.i. /scripts). *You can protect that folder by putting a .htaccess file in it with "deny from all" if you want.*
2. Install the component [ReReplacer](https://www.regularlabs.com/extensions/rereplacer) of Register Labs.  The Free version is enough. Once installed, make sure that the Regular Labs Joomla System plugin is enabled.
3. Make the same for [Sourcerer](https://www.regularlabs.com/extensions/sourcerer)  of the same editor.  The free version is enough too.  Verify that the Sourcerer system plugin is enabled too.
4. Go to the Components tab and click on ReReplacer.
5. Create a new rule
   5.1. Type `\[cavo_photo (.*?)\]` for the search pattern
   5.2. Type `{source}
   <?php
   $folder='\1';
   include JPATH_SITE.'/scripts/album_photo.php';
   ?>
   {/source}`.  Be sure to correctly specify the name of your script.
   5.3. Set "Regular expressions" to True
   5.4. And be sure to enable the rule
   <img src="https://github.com/cavo789/joomla_free/blob/master/album_photo/rereplacer.png" />
6. Save that rule.

### For each carousel

1. Create a folder like f.i. /images/blog/first
2. Put your pictures there
3. Edit a Joomla article and add this line `[cavo_photo images/blog/first]`
4. Save the article and display it

If the setup phase was correctly done, now, you should see your carousel

## Run it ##


## Remark ##


## Images ##

<img src="https://github.com/cavo789/joomla_free/blob/master/album_photo/result.png" />

## Credits ##

Christophe Avonture | [https://www.aesecure.com](https://www.aesecure.com)

---

[Get other free scripts](https://github.com/cavo789/joomla_free)