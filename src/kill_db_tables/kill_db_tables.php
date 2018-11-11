<?php

/**
 * Author : AVONTURE Christophe - https://www.avonture.be.
 *
 * Display the list of tables of your Joomla's website and allow you to remove
 * tables based on part of their name.
 *
 * This script willn't remove any table by just running it, you'll first see the
 * list of tables then you'll type a pattern (i.e. "jos" or "old_" or "_backup" or ...),
 * see the list of concerned tables and, if you confirm the deletion, only on that moment,
 * tables will be removed.
 *
 * So running the script is without danger ... until you confirm, manually, the deletion
 */

define('DEBUG', false);

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
                $return=(in_array(strtolower($tmp), ['1', 'on', 'true'])) ? true : false;
            } elseif ('string' == $type) {
                $return=filter_input(INPUT_POST, $name, FILTER_SANITIZE_STRING);
                if (true === $base64) {
                    $return=base64_decode($return);
                }
            } elseif ('unsafe' == $type) {
                $return=$_POST[$name];
            }
        } else {
            if (isset($_GET[$name])) {
                if (in_array($type, ['int', 'integer'])) {
                    $return=filter_input(INPUT_GET, $name, FILTER_SANITIZE_NUMBER_INT);
                } elseif ('boolean' == $type) {
                    // false = 5 characters
                    $tmp   =substr(filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRING), 0, 5);
                    $return=(in_array(strtolower($tmp), ['1', 'on', 'true'])) ? true : false;
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

if (isset($_SERVER['SCRIPT_FILENAME'])) {
    // In case of this script isn't in the current folder but is a symbolic link.
    // The folder should be the current folder and not the folder where the script is stored
    $sFolder=str_replace('/', DS, dirname($_SERVER['SCRIPT_FILENAME'])) . DS;
} else {
    $sFolder=__DIR__;
}

$task=aeSecureFct::getParam('task', 'string', 'init', false);

if ('' !== $task) {
    if (!file_exists($sFileName = $sFolder . 'configuration.php')) {
        echo '<p class="text-warning error">Please put this script in the same folder of your Joomla\'s <em>configuration.php</em> file i.e. in the root folder of your website.</p>';
        die();
    } else {
        require_once $sFileName;
        $JConfig = new JConfig();

        $sReturn='<h3>Working database <em>' . $JConfig->db . '</em></h3>';

        if (DEBUG === true) {
            mysqli_report(MYSQLI_REPORT_STRICT);
        }

        $mysqli = new mysqli($JConfig->host, $JConfig->user, $JConfig->password);

        if (0 !== mysqli_connect_errno()) {
            echo '<p class="bg-danger error">Could not connect to mysql.</p>';
            $mysqli->close();
            die();
        } else {
            // Be sure to work on the correct database
            mysqli_select_db($mysqli, $JConfig->db);
        }
    }
}

switch ($task) {
    case 'getList':
        $sTableList='';

        // Extract the list of table names
        $sSQL='SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA ' .
            "LIKE '" . $JConfig->db . "' ORDER BY TABLE_NAME;";

        if ($tables = $mysqli->query($sSQL)) {
            $sTableList .= '<table id="tbl" class="table tablesorter table-hover table-bordered ' .
                'table-striped"><thead><td>TableName</td><td># rows</td></thead><tbody>';

            while ($row = mysqli_fetch_array($tables)) {
                $sTableList .= '<tr><td>' . $row['TABLE_NAME'] . '</td><td>' . $row['TABLE_ROWS'] . '</td></tr>';
            }

            $sTableList .= '</tbody></table>';
        }

        $mysqli->close();

        echo $sTableList;
        die();

    case 'KillSelected':
        // The pattern is a part of the tablename like his prefix or suffix or ...
        $pattern=aeSecureFct::getParam('pattern', 'string', '', true);

        // really remove the table ?  If $doIt is set to false, a confirmation will be displayed
        $doIt=aeSecureFct::getParam('doit', 'boolean', '0', false);

        if ('' === trim($pattern)) {
            echo '<p class="bg-danger error">Your pattern is empty.  Exiting.</p>';
            $mysqli->close();
            die();
        }

        $sSQL='SELECT * FROM INFORMATION_SCHEMA.TABLES ' .
           "WHERE (TABLE_SCHEMA LIKE '" . $JConfig->db . "') AND " .
           "(TABLE_NAME LIKE '%" . $mysqli->real_escape_string(str_replace('_', '\_', $pattern)) . "%') " .
           'ORDER BY TABLE_NAME;';

        $sTableList=(1 != $doIt
            ? '<h2 class="text-danger">You\'re about to remove these tables ' .
            'from your database.  Are you sure ? </h2>'
            : '<h2>These tables have been removed</h2>');

        if ($tables = $mysqli->query($sSQL)) {
            $sTableList .= '<ul class="fa-ul">';

            while ($row = mysqli_fetch_array($tables)) {
                if (1 == $doIt) {
                    $sSQL = 'DROP TABLE `' . $mysqli->real_escape_string($row['TABLE_NAME']) . '`;';

                    if ($mysqli->query($sSQL)) {
                        $sStatus='<i class="text-success fa fa-check-square-o" aria-hidden="true"></i>';
                    } else {
                        $sStatus='<i class="text-warning fa fa-exclamation-triangle" aria-hidden="true"></i>';
                    }
                }

                $sTableList .= '<li><i class="fa-li fa fa-table"></i>' .
                    (1 != $doIt ? $row['TABLE_NAME'] : $sSQL . '&nbsp;' . $sStatus) .
                    '</li>';
            }

            $sTableList .= '</ul>';
        }

        $mysqli->close();
        echo $sTableList;
        die();

    case 'killMe':
        echo '<p class="text-success">This script (' . __FILE__ . ') has been successfully removed from your website.</p>';

        // Kill this script
        unlink(__FILE__);

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
      <title>Kill DB Prefix</title>      
      <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.25.3/css/theme.ice.min.css" rel="stylesheet" media="screen" />
      <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" media="screen" />

      <style>
        .error {
            margin:10px;
            font-size:2em;
        }
        .ajax_loading {
            display:inline-block;
            width:32px;
            height:32px;
            margin-right:20px;
        }
        .joomla {
            padding:10px;
            margin-bottom:10px;
            border:1px solid green;
        }
        .joomla ul {
            padding-top:8px;
        }
        .joomla li {
            min-width:210px;
        }
      </style>
   </head>

   <body>
        <div class="container">
            <div class="page-header"><h1>Kill DB tables</h1></div>

            <form id="form">
                <div class="joomla text-success bg-success">
                    <i class="fa fa-joomla fa-spin" aria-hidden="true"></i>&nbsp;Website : <strong><?php echo $JConfig->sitename; ?></strong>
                    <ul class="fa-ul list-inline">
                        <li><i class="fa-li fa fa-server"></i>Server : <?php echo $JConfig->host; ?></li>
                        <li><i class="fa-li fa fa-database"></i>Database : <?php echo $JConfig->db; ?></li>
                        <li><i class="fa-li fa fa-table"></i>Prefix : <?php echo $JConfig->dbprefix; ?></li>
                        <li><i class="fa-li fa fa-user"></i>User : <?php echo $JConfig->user; ?></li>
                    </ul>

                    </div>
                <div class="text-info">
                    <p>Use the text entry below to filter the list of tables to see **ONLY** those you want to remove from your database. When done press the <strong>Kill selected tables</strong> button.</p>
                    <p>The script will display the list of concerned tables for confirmation and, if you confirm the deletion, tables will then be dropped out of your database.</p>
                    <p class="text-danger">When killed, there is no way to retrieve tables (except if you've a backup) so YOU NEED TO BE REALLY SURE of what you're doing.</p>
                    <input disabled="disabled" id="search" class="filter form-control" placeholder="Type a pattern" data-column="0">&nbsp;
                </div>
                <div>
                    <button type="button" id="btnGetList" class="btn btn-primary"><i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;1. Get the list of tables</button>
                    <button type="button" disabled="disabled" id="btnKillSelected" class="hidden btn btn-warning"><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;2. Kill selected tables from your database</button>
                    <button type="button" disabled="disabled" id="btnDoIt" class="hidden btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;3. Yes, remove these tables</button>
                    <button type="button" id="btnKillMe" class="btn btn-danger pull-right" style="margin-left:10px;"><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp;Remove this script</button>
                </div>
            </form>

            <div id="Result">&nbsp;</div>

        </div>

        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.25.3/js/jquery.tablesorter.combined.min.js"></script>
        <script type="text/javascript">

        $(document).ready(function() {
           $('#btnDoIt').prop("disabled", true).addClass('hidden');
           $('#btnGetList').trigger('click');
        });

        $('#search').keydown(function (e) {
            if ($('#search').val().length>0) {
                $('#btnKillSelected').prop("disabled", false).removeClass('hidden');
            } else {
                $('#btnKillSelected').prop("disabled", true).addClass('hidden');
            }
        });

        $('#btnGetList').click(function(e)  {

            $('#search').val('');

            var $data = new Object;
            $data.task = "getList";

            $.ajax({
               beforeSend: function() {
                  $('#Result').html('<div><span class="ajax_loading">&nbsp;</span><span style="font-style:italic;font-size:1.5em;">Please wait...</span></div>');
               },
               async:true,
               type:"POST",
               url: "<?php echo basename(__FILE__); ?>",
               data:$data,
               datatype:"html",
               success: function (data) {
                  $('#Result').html(data);
                  $('#search').prop("disabled", false);
                  initTableSort();
               }
            });
            e.stopImmediatePropagation();

        });

         $('#btnKillSelected').click(function(e)  {

            e.stopImmediatePropagation();

            var $data = new Object;
            $data.task = "KillSelected";
            $data.doit= "0";
            $data.pattern = window.btoa(encodeURIComponent($('#search').val()));

            $.ajax({

               beforeSend: function() {
                  $('#Result').html('<div><span class="ajax_loading">&nbsp;</span><span style="font-style:italic;font-size:1.5em;">Please wait...</span></div>');
                  $('#btnKillSelected').prop("disabled", true);
                  $('#btnKillMe').prop("disabled", true);
                  $('#search').prop("disabled", true);
               },
               async:true,
               type:"GET",
               url: "<?php echo basename(__FILE__); ?>",
               data:$data,
               datatype:"html",
               success: function (data) {

                  $('#btnKillSelected').prop("disabled", false);
                  $('#btnKillMe').prop("disabled", false);
                  $('#search').prop("disabled", false);

                  $('#btnDoIt').prop("disabled", false).toggleClass('hidden');

                  $('#Result').html(data);

               },
               error: function(Request, textStatus, errorThrown) {
                  $('#btnKillSelected').prop("disabled", false);
                  $('#btnKillMe').prop("disabled", false);
                  // Display an error message to inform the user about the problem
                  var $msg = '<div class="bg-danger text-danger img-rounded" style="margin-top:25px;padding:10px;">';
                  $msg = $msg + '<strong>An error has occurred :</strong><br/>';
                  $msg = $msg + 'Internal status: '+textStatus+'<br/>';
                  $msg = $msg + 'HTTP Status: '+Request.status+' ('+Request.statusText+')<br/>';
                  $msg = $msg + 'XHR ReadyState: ' + Request.readyState + '<br/>';
                  $msg = $msg + 'Raw server response:<br/>'+Request.responseText+'<br/>';
                  $url='<?php echo basename(__FILE__); ?>?'+$data.toString();
                  $msg = $msg + 'URL that has returned the error : <a target="_blank" href="'+$url+'">'+$url+'</a><br/><br/>';
                  $msg = $msg + '</div>';
                  $('#Result').html($msg);
               }
            });
         });

         $('#btnDoIt').click(function(e)  {

            e.stopImmediatePropagation();

            var $data = new Object;
            $data.task = "KillSelected";
            $data.doit= "1";
            $data.pattern = window.btoa(encodeURIComponent($('#search').val()));

            $.ajax({

               beforeSend: function() {
                  $('#Result').html('<div><span class="ajax_loading">&nbsp;</span><span style="font-style:italic;font-size:1.5em;">Please wait...</span></div>');
                  $('#btnKillSelected').prop("disabled", true);
                  $('#btnKillMe').prop("disabled", true);
                  $('#btnDoIt').prop("disabled", true);
                  $('#search').prop("disabled", true);
               },
               async:true,
               type:"GET",
               url: "<?php echo basename(__FILE__); ?>",
               data:$data,
               datatype:"html",
               success: function (data) {

                  $('#btnKillSelected').prop("disabled", true).addClass('hidden');
                  $('#btnDoIt').prop("disabled", true).toggleClass('hidden');

                  $('#btnGetList').prop("disabled", false).removeClass('hidden');
                  $('#btnKillMe').prop("disabled", false);

                  $('#search').prop("disabled", false);
                  $('#search').val('');

                  $('#Result').html(data);

               },
               error: function(Request, textStatus, errorThrown) {
                  $('#btnKillSelected').prop("disabled", false);
                  $('#btnDoIt').prop("disabled", false);
                  // Display an error message to inform the user about the problem
                  var $msg = '<div class="bg-danger text-danger img-rounded" style="margin-top:25px;padding:10px;">';
                  $msg = $msg + '<strong>An error has occurred :</strong><br/>';
                  $msg = $msg + 'Internal status: '+textStatus+'<br/>';
                  $msg = $msg + 'HTTP Status: '+Request.status+' ('+Request.statusText+')<br/>';
                  $msg = $msg + 'XHR ReadyState: ' + Request.readyState + '<br/>';
                  $msg = $msg + 'Raw server response:<br/>'+Request.responseText+'<br/>';
                  $url='<?php echo basename(__FILE__); ?>?'+$data.toString();
                  $msg = $msg + 'URL that has returned the error : <a target="_blank" href="'+$url+'">'+$url+'</a><br/><br/>';
                  $msg = $msg + '</div>';
                  $('#Result').html($msg);
               }
            });
         });

        // Remove this script
        $('#btnKillMe').click(function(e)  {
           e.stopImmediatePropagation();

           var $data = new Object;
           $data.task = "killMe";

           $.ajax({
              beforeSend: function() {
                 $('#Result').empty();
                 $('#btnKillSelected').prop("disabled", true);
                 $('#btnKillMe').prop("disabled", true);
              },
              async:true,
              type:"POST",
              url: "<?php echo basename(__FILE__); ?>",
              data:$data,
              datatype:"html",
              success: function (data) {
                 $('#form').remove();
                 $('#Result').html(data);
              }
           });
        });

        function initTableSort() {

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
                  uitheme: "ice",
                  filter_columnFilters: false
               },
               sortList: [[0]]  // Sort by default on the table name
            });

            // Bind the input
            $.tablesorter.filter.bindSearch( $("#tbl"), $('.filter') );

         }
      </script>

   </body>
</html>
