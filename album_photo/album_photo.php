<!--

Author : AVONTURE Christophe - https://www.aesecure.com

Add an image carousel in your Joomla article. Just save images in a folder of your site (f.i. /images/blog/alias/), 
add a specific tag in your article and thumbnails will be displayed
 
Requires : ReReplacer and Sourcerer of Register Labs (https://www.regularlabs.com/extensions/)
 
How to : read https://github.com/cavo789/joomla_free

Setup (to do only once)
-----
 
1. Save this script somewhere on your site (f.i. /scripts/album_photo.php)
2. Install ReReplacer (https://www.regularlabs.com/extensions/rereplacer) and publish the ReReplacer system plugin
3. Install Sourcerer (https://www.regularlabs.com/extensions/sourcerer), publish the plugin too.
4. In ReReplacer, add this rule : 
 
   Search : 
      \[cavo_photo (.*?)\]
 	  
   Replace by : 
      {source}
 	  &lt?php
      $folder='\1';
      include JPATH_SITE.'/scripts/album_photo.php';
      ?&gt;
      {/source}   
 	  
   Set "Regular expressions" to True and be sure the rule is enabled.	   
 
Use it
------
 
1. Create a folder in your /images folder where you'll put your images f.i. /images/blog/alias
2. Save there your images
3. Add a line like "[cavo_photo images/blog/alias]" in your Joomla article
4. Save the article and display it on your frontend. 
  
Go to your site and refresh your article. You should now see your image carousel.

-->
 
<style type="text/css">
.carousel {margin-bottom:0;padding:0 40px 30px 40px;}
.carousel-control {left:-12px;}
.carousel-control.right {right:-12px;}
.carousel-indicators {right:50%;top:auto;bottom:0px;margin-right:-19px;}
.carousel-indicators li {background:#c0c0c0;}
.carousel-indicators .active {background:#333333;}
</style>

<?php

   // NOTE : la variable $folder doit être initialisée par le code qui appelle celui-ci
   // $folder doit être le nom d'un dossier se trouvant à la racine du site.   Par exemple "images/blog/album_photo".

   // Initialiser à 1 si, lorsqu'on clique sur une vignette, si l'image doit s'afficher dans une fenêtre modale.
   $useModal=1;
  
   // ---------------------------------------------------------------------------------------------------------------
    
   jimport('joomla.filesystem.file');
   jimport('joomla.filesystem.folder');

   // Nombre d'images par slides
   $imgBySlides=4;
  
   // Contrôle que le dossier existe
  
   if (($folder!='') && (is_dir(JPATH_SITE.'/'.$folder))) {  
  
      $arrFiles = JFolder::files(JPATH_SITE.'/'.$folder, '.', 0, 0, array('..', '.', 'index.htm', 'index.html'));

      $count=count($arrFiles);
  
      if ($count>0) {
     
         // Compte le nombre de slides : divise le nombre d'images trouvé par le nombre d'images par slides et augmente de 1 s'il reste un solde
         $slides=intval($count/$imgBySlides);
          if (($count % $imgBySlides)>0) $slides+=1;

          // -----------------------------------------------------------------------
          //
         // Génère les fenêtes modales
         
          if ($useModal==1) {
         
              echo '<div id="imgModal">';
              
              $wPicture=0;
              
              for ($i=0;$i<$slides; $i++) {
              
               for ($j=0;$j<$imgBySlides;$j++) {
                 
                  if (isset($arrFiles[$wPicture])) {    
                     
                        $img=JURI::base().$folder.'/'.$arrFiles[$wPicture];
                         echo '<div class="modal fade" id="mod_img'.$wPicture.'" data-remote=""><img src="'.$img.'" title="'.$img.'" alt="'.$arrFiles[$wPicture].'"/></div>' ;

                  } // if (isset($arrFiles[$wPicture])) {     

                  $wPicture+=1;

               } // for ($j=0;$j<$imgBySlides;$j++) {

            } // for ($i=0;$i<$slides; $i++) {

              echo '</div><!--/imgModal-->';

          } // if ($useModal==1)
         
          // -----------------------------------------------------------------------
         
          // Génère le caroussel et les vignettes
         
         echo '<div class="well">';
         echo '<div id="myCarousel" class="carousel slide">';
         echo '<ol class="carousel-indicators">';

         for ($i=0;$i<$slides;$i++) {         
             echo '<li data-target="#myCarousel" data-slide-to="'.$i.'" class="'.($i==0?'active':'').'"></li>';
         }
      
          echo '</ol>';
          echo '<div class="carousel-inner">';

          $wPicture=0;
          for ($i=0;$i<$slides; $i++) {
         
             echo '<div class="item '.($i==0?'active':'').'">';
             echo '<div class="row-fluid">';
              
            for ($j=0;$j<$imgBySlides;$j++) {
              
                  if (isset($arrFiles[$wPicture])) {
                 
                     $img=JURI::base().$folder.'/'.$arrFiles[$wPicture];
                     
                      // Contrôle s'il y a une miniature dans un sous-dossier nommé Thumbs.  Si oui, utilise la miniature; si non, utilise l'image originale.
                      $imgThumb=JPATH_SITE.'/'.$folder.'/Thumbs/'.$arrFiles[$wPicture];                     
                      $imgThumb = (is_file($imgThumb)?str_replace(JPATH_SITE,JURI::base(),$imgThumb):$img);

                    echo '<div class="span3"><a '.($useModal==1 ? 'data-target="#mod_img'.$wPicture.'" data-toggle="modal"':'').' href="'.$img.'" class="thumbnail"><img src="'.$imgThumb.'" title="'.$imgThumb.'" alt="'.$arrFiles[$wPicture].'" style="max-width:100%;" /></a></div>';
                
                  } else { // if (isset($arrFiles[$wPicture]))
                 
                     echo '<div class="span3">&nbsp;</div>';
                     
                  } // if (isset($arrFiles[$wPicture]))

               $wPicture+=1;
                 
            } // for ($j=0;$j<$imgBySlides; $j++)
              
            echo '</div></div>';
              
          } // for ($i=0;$i<slides;$i++)
      
          echo '</div><!--/carousel-inner-->';
          echo '<a class="left carousel-control" href="#myCarousel" data-slide="prev">‹</a>';
          echo '<a class="right carousel-control" href="#myCarousel" data-slide="next">›</a>';
          echo '</div><!--/myCarousel-->';
          echo '</div><!--/well-->';
      
      } // if ($count>0)     
      
   } else { // if (is_dir($folder))  
  
      echo '<h1 class="alert alert-danger">Le dossier '.$folder.' est inexistant.</h1>';
      
   } // if (is_dir($folder))  

?>