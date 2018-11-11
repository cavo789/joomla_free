<?php

/**
 * Author : AVONTURE Christophe - https://www.avonture.be.
 *
 * Very straight forward script to check if the database connection is working.
 * This script should be placed in the root folder of your Joomla website.
 * Then just call it : http://yoursite/check_db.php
 *
 * If your database is correctly configured in your Joomla's configuration.php file,
 * this script will initialize a connection to the database server and will get the
 * list of all tables with, for each of them, the number of records.
 *
 * If a connectivity problem is found, the script will display technical information's
 */

define('DEBUG', true);

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

class aeSecureFct
{
    /**
     * Safely read posted variables.
     *
     * @param type  $name    f.i. "password"
     * @param type  $type    f.i. "string"
     * @param type  $default f.i. "default"
     * @param mixed $base64
     *
     * @return type
     */
    public static function getParam($name, $type = 'string', $default = '', $base64 = false)
    {
        $tmp   ='';
        $return=$default;

        if (isset($_POST[$name])) {
            if (in_array($type, ['int', 'integer'])) {
                $return=filter_input(INPUT_POST, $name, FILTER_SANITIZE_NUMBER_INT);
            } elseif ('boolean' == $type) {
                // false = 5 characters
                $tmp   =substr(filter_input(INPUT_POST, $name, FILTER_SANITIZE_STRING), 0, 5);
                $return=(in_array(strtolower($tmp), ['on', 'true'])) ? true : false;
            } elseif ('string' == $type) {
                $return=filter_input(INPUT_POST, $name, FILTER_SANITIZE_STRING);
                if (true === $base64) {
                    $return=base64_decode($return);
                }
            } elseif ('unsafe' == $type) {
                $return=$_POST[$name];
            }
        } else { // if (isset($_POST[$name]))
            if (isset($_GET[$name])) {
                if (in_array($type, ['int', 'integer'])) {
                    $return=filter_input(INPUT_GET, $name, FILTER_SANITIZE_NUMBER_INT);
                } elseif ('boolean' == $type) {
                    // false = 5 characters
                    $tmp   =substr(filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRING), 0, 5);
                    $return=(in_array(strtolower($tmp), ['on', 'true'])) ? true : false;
                } elseif ('string' == $type) {
                    $return=filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRING);
                    if (true === $base64) {
                        $return=base64_decode($return);
                    }
                } elseif ('unsafe' == $type) {
                    $return=$_GET[$name];
                }
            }
        }

        if ('boolean' == $type) {
            $return=(in_array($return, ['on', '1']) ? true : false);
        }

        return $return;
    }
}

if (DEBUG === true) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    ini_set('html_errors', '1');
    ini_set('docref_root', 'http://www.php.net/');
    ini_set('error_prepend_string', "<div style='color:red; font-family:verdana; border:1px solid red; padding:5px;'>");
    ini_set('error_append_string', '</div>');
    error_reporting(E_ALL);
} else {
    ini_set('error_reporting', E_ALL & ~E_NOTICE);
}

$task=aeSecureFct::getParam('task', 'string', '', false);

if ('doIt' === $task) {
    $sReturn = '';

    require_once 'configuration.php';
    $JConfig = new JConfig();

    $sReturn .= '<h2>Database credentials</h2><em>Found in your configuration.php file.</em><ul><li>Host : ' . $JConfig->host . '</li>' .
    '<li>Type : ' . $JConfig->dbtype . '</li>' .
    '<li>Database : ' . $JConfig->db . '</li>' .
    '<li>Username : ' . $JConfig->user . '</li><li>Password : ' . $JConfig->password . '</li>' .
    '<li>Prefix : ' . $JConfig->dbprefix . '</li></ul>';

    mysqli_report(MYSQLI_REPORT_STRICT);

    $mysqli = new mysqli($JConfig->host, $JConfig->user, $JConfig->password);

    if (0 === mysqli_connect_errno()) {
        $sReturn .= '<h2>MySQL version : ' . $mysqli->server_info . '</h2>';

        $sSQL="SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE '" . $JConfig->db . "' ORDER BY TABLE_NAME;";

        $sReturn .= '<h2>List of tables found in your database</h2><pre lang="sql">' . $sSQL . '</pre>';

        if ($tables = $mysqli->query($sSQL)) {
            $sReturn .= '<table id="tbl" class="table tablesorter table-hover table-bordered table-striped"><thead><td>TableName</td><td># rows</td></thead><tbody>';
            while ($row = mysqli_fetch_array($tables)) {
                $sReturn .= '<tr><td>' . $row['TABLE_NAME'] . '</td><td>' . $row['TABLE_ROWS'] . '</td></tr>';
            }
            $sReturn .= '</tbody></table>';
        }

        unset($row, $tables);
    } else {
        $sReturn .= '<p class="bg-danger">Could not connect to mysql.</p>';
        $sReturn .= '<h2>MySQL object</h2><pre>' . print_r($mysqli, true) . '</pre>';
    }

    $mysqli->close();

    $sReturn .= '<script>initSort();</script>';

    echo $sReturn;
    die();
}

?>

<!DOCTYPE html>
<html lang="en">

   <head>
      <meta charset="utf-8"/>
      <meta name="author" content="Christophe Avonture" />
      <meta name="robots" content="noindex, nofollow" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8;" />
      <title>Check DB connection</title>      
      <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.25.3/css/theme.ice.min.css" rel="stylesheet" media="screen" />

      <style>
         .ajax_loading {display:inline-block;
            width:32px;
            height:32px;
            margin-right:20px;            
          }
      </style>
   </head>

   <body>
      <div class="container">
         <div class="page-header"><h1>Check database connection</h1></div>
         <div id="Result">&nbsp;</div>
      </div>
      <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
      <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.25.3/js/jquery.tablesorter.combined.min.js"></script>
      <script type="text/javascript">

         $(document).ready(function() {
            var $data = new Object;
            $data.task = "doIt";

            $.ajax({
               beforeSend: function() {
                  $('#Result').html('<div><span class="ajax_loading">&nbsp;</span><span style="font-style:italic;font-size:1.5em;">Un peu de patience svp...</span></div>');
               },
               async:true,
               type:"POST",
               url: "<?php echo basename(__FILE__); ?>",
               data:$data,
               datatype:"html",
               success: function (data) {
                  $('#Result').html(data);
               }
            });
         });

         function initSort() {

            $("#tbl").tablesorter({
                theme: "ice",
                widthFixed: false,
                sortMultiSortKey: "shiftKey",
                sortResetKey: "ctrlKey",
                headers: {
                   0: {sorter: "text"}, // Table name
                   1: {sorter: "digit"} // Number of records
                },
                ignoreCase: true,
                headerTemplate: "{content} {icon}",
                widgets: ["uitheme", "filter"],
                initWidgets: true,
                widgetOptions: {
                   uitheme: "ice"
                },
                sortList: [[0]]  // Sort by default on the table name
             });
          }
      </script>
   </body>
</html>
