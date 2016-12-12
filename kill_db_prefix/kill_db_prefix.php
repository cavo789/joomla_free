<?php

// Ce script va afficher la liste des tables de votre base de données.  
// Le paramètre "prefix" dans le querystring va vous permettre d'indiquer 
// un préfixe (p.e. "tmp_"). Toutes les tables dont le nom commencera 
// par ce préfixe seront supprimées dans votre base de données
//
// Exemple : http://localhost/drop.php?prefix=tmp_
//
// Script php créé par Paul Ellis en se basant sur du code trouvé sur www.php.net. 
// http://www.ellisfoundation.com/freemusic/
// Modifié par Brian Simecek pour l'ajout de quelques messages d'erreurs
// Modifié par Christophe Avonture pour un affichage plus efficace, un code plus compact et une intégration avec le CMS Joomla
   
   if (file_exists('configuration.php')) {
       
      require_once('configuration.php');
      $config = new JConfig();   
      $con = mysql_connect($config->host, $config->user, $config->password);   
          
          if ($con==false) { print 'Could not connect to mysql'; die(); }
   
      // Variables
      $tablesucess=false;

      $result = mysql_list_tables($config->db);
      if (!$result) { print "DB Error, could not list tables\nMySQL Error: " . mysql_error();exit; }
   
      echo '<h3>TABLE LIST (database='.$config->db.'):</h3>';
      echo '<h5>Syntax to drop multiple tables by prefix is "'.basename(__FILE__).'?prefix=prefix_that_the_tables_have"</h5>';
      echo '<ol>';
   
      if (!(isset($_GET['prefix'])) || (empty($_GET['prefix'])) ) {
         while ($row = mysql_fetch_row($result)) echo '<li>'.$row[0].'</li>';
         $tablesucess=true;   
      } else {
         echo '<ol>';
         while ($row = mysql_fetch_row($result)) {
            if (substr($row[0], 0, strlen($_GET['prefix'])) == $_GET['prefix']) {
               $tablesucess = true;
               $query = "DROP TABLE `".$row[0]."`";
               $status = mysql_query("$query");                 
               if ($status) {
                      echo '<li>'.$row[0].' has been dropped</li>';
               } else {
                  echo '<li>Failure when dropping '.$row[0].'</li>';
                      die(mysql_error());
               }
            }
         } 
      }
          
      echo '</ol>';

      if (!$tablesucess) { echo  '<h5>Prefix Does Not Match Any Tables Within database '.$config->db.'</h5>'; die(); }

      mysql_free_result($result);
   
   } else {
   
      print 'configuration.php file of Joomla not found.';
          
   } // if (file_exists('configuration.php'))
   
?>