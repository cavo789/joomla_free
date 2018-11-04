<?php

/**
 * Simplified version of show_table.php.
 * Display the list of users (table #_users); the most recent user first.
 * Show all fields of the table.
 *
 * ==> Password to use is "users"
 */

define('SQL', 'SELECT * FROM `#__users` ORDER BY `registerDate` DESC');

define('DS', DIRECTORY_SEPARATOR);

// Password to use.  The default one is "users"
// If you want to change, use an online tool like f.i. http://www.md5.cz/
define('PASSWORD', '9bc65c2abec141778ffaa729489f3e87');

// The SQL variable
$sSQL = '';

/**
 * Called in by array_walk($arr, "displaySQL");
 * For each items in $arrKeyword, this function is called.
 * Idea is to insert a line break before a "SELECT", "FROM", "WHERE", "LIMIT" and "ORDER BY" to
 * improve readability of the SQL statement.
 *
 * @param [type] $keyword
 *
 * @return void
 */
function displaySQL(&$keyword)
{
    global $sSQL;

    $sSQL = str_replace($keyword, '<br/>' . $keyword, $sSQL);
}

// Check if the password is valid; if not, stop immediatly

$sParamsPassword = filter_input(INPUT_GET, 'password', FILTER_SANITIZE_STRING);

if (PASSWORD !== md5($sParamsPassword)) {
    header('HTTP/1.0 403 Forbidden');
    echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="GET">Password: <input type="text" name="password"/><input type="submit"/></form>';
    die();
}

if (isset($_SERVER['SCRIPT_FILENAME'])) {
    $root = str_replace('/', DS, dirname(dirname($_SERVER['SCRIPT_FILENAME'])) . DS);
} else {
    $root = __DIR__;
}

// --------------------------------------------------------------------------------------
// Get filters
// RAW or HTML output

$sParamsFormat = trim(strtoupper(filter_input(INPUT_GET, 'format', FILTER_SANITIZE_STRING)));

$RAW = ('RAW' === $sParamsFormat);

// Limit the list to a specific customer's country ?

$sParamsCountry = trim(strtoupper(filter_input(INPUT_GET, 'country', FILTER_SANITIZE_STRING)));

// --------------------------------------------------------------------------------------
// Load the Joomla framework

if (!defined('_JEXEC')) {
    define('_JEXEC', 1);
}

if (!defined('JPATH_BASE')) {
    define('JPATH_BASE', rtrim($root, DS));
}

if (!defined('JPATH_PLATFORM')) {
    define('JPATH_PLATFORM', $root . DS . 'libraries');
}

//include joomla core files (disable errors because Joomla produde WARNINGs and NOTICES)

$error = error_reporting();

error_reporting(0);

if (file_exists($fname = JPATH_BASE . '/includes/defines.php')) {
    require_once $fname;
}

if (file_exists($fname = JPATH_BASE . '/includes/framework.php')) {
    require_once $fname;
}

// No more present since J3.2
if (file_exists($fname = JPATH_BASE . '/includes/application.php')) {
    require_once $fname;
}

if (file_exists($fname = JPATH_BASE . '/libraries/joomla/factory.php')) {
    require_once $fname;
}

if (file_exists($fname = JPATH_BASE . '/libraries/joomla/log/log.php')) {
    require_once $fname;
}

error_reporting($error);

require_once JPATH_BASE . '/configuration.php';

// Start the output

echo '<!DOCTYPE html><html lang="en">' .
    '<head>' .
    '<meta charset="utf-8"/>' .
    '<meta http-equiv="content-type" content="text/html; charset=UTF-8" />' .
    '<meta name="robots" content="noindex, nofollow" />' .
    '<meta name="author" content="Christophe Avonture" />' .
    '<meta name="viewport" content="width=device-width, initial-scale=1.0" />' .
    '<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8;" />';

if (true !== $RAW) {
    echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">';
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.0/css/theme.ice.min.css" media="screen" />';
}

echo '</head><body>';

if (true !== $RAW) {
    echo '<div class="alert alert-info alert-dismissible fade show" role="alert">' .
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
        '<p>Allowed query parameters : <ul>' .
        '<li>format=(RAW|HTML) - Choice RAW for linking this list in Excel</li>' .
        '<li>password=xxxx (the required password to get access to that page, <strong>mandatory</strong></li>' .
        '</ul></p></div>';
    echo '<div style="margin:10px;">';
    echo '<h1>List of users</h1>';
}

// ----------------------------------------------------------------------------------
// Get the database object of Joomla

$db = JFactory::getDBO();

// Build the querystring, sanitize criterias

$sWHERE = '';

$sSQL = SQL;

// Apply the conditions

if ('' != $sWHERE) {
    $sSQL = substr($sSQL, 0, strpos($sSQL, 'ORDER BY')) . 'WHERE ' . $sWHERE . ' ' . substr($sSQL, strpos($sSQL, 'ORDER BY'));
}

// -------------------------------------
// Run the query and display the table

$db->setQuery($sSQL);

$rows = $db->loadObjectList();

if (0 == count($rows)) {
    echo 'No records found';

    die();
}

echo '<table id="tbl" class="table tablesorter table-striped">';

// Output the list of fields name

echo '<thead><tr>';

$arrExactMatch = ['country', 'gender', 'invoicenumber', 'language', 'month', 'payment_status', 'paymentid', 'userid', 'year'];

foreach ($rows[0] as $field => $value) {
    $class = in_array(strtolower($field), $arrExactMatch) ? 'filter-select filter-exact' : '';

    echo '<th class="' . $class . '">' . $field . '</th>';
}

echo '</tr></thead>';
echo '<tbody>';

foreach ($rows as $row) {
    $line = '';

    $class = '';

    if (('PRODUCTS' == $sParamsObject) && ((int)$row->PaidVATAmount > 0)) {
        $class = 'success';
    }

    foreach ($row as $field => $value) {
        // If it's an amount, only keep 2 figures after the decimal part and show the number with a comma (French style); not a dot (International)

        if ('amount' == strtolower(substr($field, -6))) {
            $value = number_format($value, 2, ',', ' ');
        }

        $line .= '<td>' . trim($value) . '</td>';
    }

    echo '<tr class="' . $class . '">' . $line . '</tr>';
}

echo '</tbody>';
echo '</table>';

if (true != $RAW) {
    echo '<p><strong>Nombre d\'enregistrements&nbsp;:&nbsp;' . number_format(count($rows), 0, ',', ' ') . '</strong></p>';

    $arrKeyword = ['FROM ', 'INNER JOIN', 'LEFT JOIN', 'RIGHT JOIN', 'WHERE ', 'ORDER BY ', 'LIMIT '];

    array_walk($arrKeyword, 'displaySQL');

    echo '<p><strong>SQL used :</strong><br/><code>' . trim($sSQL) . '</code></p>';
    echo '</div>';
}

if (true != $RAW) {
    echo '<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>';
    echo '<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>';
    echo '<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.0/js/jquery.tablesorter.combined.min.js"></script>';

    echo '<script>' .
        '$("#tbl").tablesorter({' .
        '   theme: "ice",' .
        '   widthFixed: false,' .
        '   sortMultiSortKey: "shiftKey",' .
        '   sortResetKey: "ctrlKey",' .
        '   ignoreCase: true,' .
        '   headerTemplate: "{content} {icon}",' .
        '   widgets: ["uitheme", "filter"],' .
        '   initWidgets: true,' .
        '   widgetOptions: {' .
        '      filter_reset: ".reset", ' .
        '      uitheme: "ice"' .
        '   },' .
        '});</script>';

    echo '<script>' .
        '$("button").click(function(){' .
        'var $t = $(this),' .
        'col = $t.data("filter-column"),' .
        'filter = [];' .
        'filter[col] = $t.data("filter-txt");	' .
        '$("#tbl").trigger("search", [ filter ]);' .
        'return false;' .
        '});</script>';
}

echo '</body></html>';
