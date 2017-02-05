<?php

ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
ini_set("html_errors", "1");
ini_set("docref_root", "http://www.php.net/");
ini_set("error_prepend_string", "<div style='color:red; font-family:verdana; border:1px solid red; padding:5px;'>");
ini_set("error_append_string", "</div>");
error_reporting(E_ALL);

if (function_exists('phpinfo')) {
    echo "<h1>phpinfo() - aeSecure - Ce script s'auto-supprime lors de sa première exécution</h1>";
    phpinfo();
} else {
    echo '<h1>Fucking host; phpinfo() has been disabled</h1>';
}

unlink(__FILE__);
