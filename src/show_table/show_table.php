<?php
/**
 * Christophe Avonture - https://www.aesecure.com
 * Written date  : 2016-10-16
 * Last modified : 2017-10-04
 *
 * Changes
 * -------
 * 2017-10-04 - Add export buttons (use https://datatables.net/ and no more tablesorter)
 *
 * Description
 * -----------
 * This small script will execute a SQL statement against the database
 * of your Joomla website and will show the result in a nice HTML
 * table (bootstrap).
 * When the output is HTML, the DataTables plugin will be used to
 * provide extra functionnalities like sorting and filtering.
 *
 * Parameters :
 *
 *   * password : the password define in the PASSWORD constant.
 *
 *   * format   : can be 'HTML' (default) or 'RAW'
 *                RAW will only output a table tag without html headers
 *                or javascript. RAW will be usefull when f.i. the table
 *                will be used in a spreadsheet application or as input for
 *                an another program.
 *                For instance : in Excel, you can create a Data Query.
 *                Use the &format=RAW parameter to get a perfect table for Excel.
 *
 *    Add yours : Add your own parameters !
 *    For instance a filter (period=xxxx), a selection (tablename=a_table),
 *    a limit (limit=10), ...
 *
 * Example : https://youriste/show_table.php?password=Joomla&format=RAW
  */

namespace aeSecure;

define('TITLE', 'Example of Show_Table');

// This is an example : this SQL will retrieve all users defined in your
// database and will return ID, name, pseudo, email, register date, last visit
// date and the group of the user (registered, super-users, ...)

define(
	'SQL',
	'SELECT U.id UserID, U.name Name, U.username UserName, '.
	'U.email eMail, U.registerDate RegisterDate, '.
	'U.lastvisitDate LastVisitDate, G.title GroupTitle '.
	'FROM `#__users` U '.
	'LEFT JOIN (`#__user_usergroup_map` as UG) ON UG.user_id=U.id '.
	'LEFT JOIN (`#__usergroups` as G) on UG.group_id=G.id '.
	'ORDER BY registerDate DESC, name, GroupTitle ASC'
);

// SQL statement for retrieving informations from, f.i., the content table
/*
define(
	'SQL',
	'SELECT C.id As Article_ID, C.title As Article_Title, '.
	'G.title As Category_Title, '.
	'U.name As Author_Name, C.Hits As Hits, C.language As Language, '.
	'C.created As Writen_Date '.
	'FROM `#__content` C LEFT JOIN `#__categories` G ON C.catid = G.id '.
	'LEFT JOIN `#__users` U on C.created_by=U.id '.
	'WHERE (state=1) '.
	'ORDER BY C.created DESC'
);
*/
define('DEBUG', false);
define('DS', DIRECTORY_SEPARATOR);

// Root folder of Joomla. If you've save this script in the root
// folder of Joomla, just leave __DIR__ otherwise you'll need
// to update this constant and specify your own root
define('ROOT', __DIR__);
//define('ROOT', 'C:\Christophe\Sites\beta');

// Use this line instead the previous if you've put the script in
// a subfolder of your website root
//define('ROOT',dirname(__DIR__));

// Password to use.  The default one is "Joomla"
// If you want to change, use an online tool like f.i. http://www.md5.cz/
define('PASSWORD', '57ac91865e5064f231cf620988223590');

class ShowTable
{
	private static $format = '';

	public function __construct()
	{
		if (DEBUG===true) {
			ini_set("display_errors", "1");
			ini_set("display_startup_errors", "1");
			ini_set("html_errors", "1");
			ini_set("docref_root", "http://www.php.net/");
			ini_set("error_prepend_string", "<div style='color:red; font-family:verdana; border:1px solid red; padding:5px;'>");
			ini_set("error_append_string", "</div>");
			error_reporting(E_ALL);
		} else {
			ini_set('error_reporting', E_ALL & ~ E_NOTICE);
		}

		// Die if the pasword isn't supplied
		self::checkPassword();

		// Die if no configuration.php file found
		self::checkConfiguration();

		// Load Joomla framework
		self::loadConfiguration();

		// Get the requested format : HTML or RAW.
		// If nothing is specified, HTML will be the default one
		static::$format = filter_input(INPUT_GET, 'format', FILTER_SANITIZE_STRING);
		static::$format = strtoupper(static::$format);
		if (!in_array(static::$format, array('HTML','RAW'))) {
			static::$format='HTML';
		}

		$RAW=(static::$format==='RAW');

		return true;
	}

	/**
	  * Check if the password is valid; if not, stop immediatly
	 */
	private function checkPassword()
	{
		// Get the password from the query string
		$password=filter_input(INPUT_GET, 'password', FILTER_SANITIZE_STRING);

		if (md5($password) !== PASSWORD) {
			header('HTTP/1.0 403 Forbidden');
			echo '<form action="'.$_SERVER['PHP_SELF'].'" method="GET">Password: <input type="text" name="password" /><input class="Submit" type="submit" name="submit" /></form>';
			die();
		}
	}

	/**
	 * Die if no configuration.php file found
	 */
	private function checkConfiguration()
	{
		if (!file_exists($config = rtrim(ROOT, DS).DS.'configuration.php')) {
			die('<strong>The file '.$config.' can\'t be found, please review the ROOT constant to match your website root folder</strong>');
		}
		return true;
	}

	/**
	* Load Joomla framework
	*/
	private function loadConfiguration()
	{
		if (!defined('_JEXEC')) {
			define('_JEXEC', 1);
		}

		if (!defined('JPATH_BASE')) {
			define('JPATH_BASE', rtrim(ROOT, DS));
		}

		if (!defined('JPATH_PLATFORM')) {
			define('JPATH_PLATFORM', rtrim(ROOT, DS).DS.'libraries');
		}

		// include joomla core files (disable errors because
		// Joomla produde WARNINGs and NOTICES)
		$error=error_reporting();
		error_reporting(0);

		if (file_exists($fname = JPATH_BASE.'/includes/defines.php')) {
			require_once($fname);
		}

		if (file_exists($fname = JPATH_BASE.'/includes/framework.php')) {
			require_once($fname);
		}

		if (file_exists($fname = JPATH_BASE.'/includes/application.php')) {
			require_once($fname);       // No more present since J3.2
		}

		if (file_exists($fname = JPATH_BASE.'/libraries/joomla/factory.php')) {
			require_once($fname);
		}

		if (file_exists($fname = JPATH_BASE.'/libraries/joomla/log/log.php')) {
			require_once($fname);
		}

		error_reporting($error);

		require_once(JPATH_BASE.'/configuration.php');

		return true;
	}

	/**
	 * Run the query and return the recordset
	 */
	public function getRows()
	{
		$rows = array();

		try {
			$db = \JFactory::getDBO();
			$db->setQuery(SQL);

			$rows = $db->loadObjectList();
		} catch (Exception $e) {
			echo $e->getMessage();
		}

		return $rows;
	}

	/**
	 * Add CSS to the page
	 */
	public function addCSS()
	{
		$script = "";
		if (static::$format==='HTML') {
			$arr=array(
				'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
				'https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css',
				'https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css'
			);

			foreach ($arr as $style) {
				$script .= "<link rel='stylesheet' href='".$style."' ".
					"rel='stylesheet' media='screen' />\n";
			}
		}

		return $script."\n";
	}

	/**
	 * Add JS to the page
	 */
	public function addJS()
	{
		$script = "";

		if (static::$format==='HTML') {
			$arr=array(
				'//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js',
				'//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js',
				'//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js',
				'//cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js',
				'//cdn.datatables.net/buttons/1.4.2/js/buttons.flash.min.js',
				'//cdn.datatables.net/buttons/1.4.2/js/buttons.print.min.js',
				'//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js',
				'//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js',
				'//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js',
				'//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js'
			);

			foreach ($arr as $js) {
				$script .=  "<script type='text/javascript' src='".$js."'></script>\n";
			}

			// Initialize scripts
			$script .= "<script type='text/javascript'>\n".
				"$(document).ready(function () {\n".
				"	// Setup - add a text input to each footer cell\n".
				"	$('#tbl tfoot th').each( function () {\n".
				"		$(this).html('<input type=\"text\" placeholder=\"Search\" />');\n".
				"	});\n".
				"	\n".
				"	$('#tbl').DataTable({\n".
				"		'fixedHeader': false,\n".
				"		'scrollY': '60vh',\n".
				"		'scrollCollapse': true,\n".
				"		'info': true,\n".
				"   	'fixedHeader': true,\n".
				"   	'dom' : 'Bfrtip',\n".
				"   	'buttons' : ['copy', 'csv', 'excel', 'pdf', 'print'], \n".
				"		'lengthMenu': [ \n".
				"			[25, 50, 100, 500, -1], \n".
				"			[25, 50, 100, 500, 'All'] \n".
				"		] \n".
				"	});\n".
				"	\n".
				"	// Apply the search\n".
				"   var tbl = $('#tbl').DataTable();\n".
				"	tbl.columns().every(function(){\n".
				"		var that = this;\n".
				"		$('input', this.footer()).on('keyup change', function(){\n".
				"			if (that.search() !== this.value) {\n".
				"				that.search(this.value).draw();\n".
				"			}\n".
				"		});\n".
				"	});\n".
				"});\n".
				"</script>";
		}

		return $script;
	}

	public function outputTable()
	{
		$rows = self::getRows();

		$return = '';

		if (count($rows)>0) {
			// Output the table
			$table = '<table id="tbl" class="display compact nowrap order-column">';

			// Output the list of fields name
			$line='';

			foreach ($rows[0] as $field => $value) {
				$line.='<th>'.$field.'</th>';
			}

			$table .=
				'<thead><tr>'.$line.'</tr></thead>'.
				'<tfoot><tr>'.$line.'</tr></tfoot>'.
				'<tbody>';

			foreach ($rows as $row) {
				$line='';
				foreach ($row as $field => $value) {
					$line.='<td>'.$value.'</td>';
				}
				$table .= '<tr>'.$line.'</tr>';
			} // foreach

			$table .= '</tbody></table>';
		} // if (count($rows)>0)

		$return = $table;

		if (static::$format==='HTML') {
			// Get a few informations
			$infos = '<p><strong>Number of records &nbsp;:&nbsp;'.
				number_format(count($rows)).'</strong></p>';

			$sTitle = trim(TITLE);
			if ($sTitle!=='') {
				$sTitle = "<h1>".$sTitle."</h1>";
			}

			$return = '<div style="margin:10px;">'.$sTitle.$table.$infos.'</div>';
		}

		return  $return;
	}
}

$showTable = new \aeSecure\ShowTable();

?>

<!DOCTYPE html><html lang="en">
	<head>
		<meta charset="utf-8"/>
		<meta name="robots" content="noindex, nofollow" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8;" />
		<?php echo $showTable->addCSS(); ?>
	</head>
	<body>
		<?php echo $showTable->outputTable(); ?>
		<?php echo $showTable->addJS(); ?>
	</body>
</html>

<?php
unset($showTable);
?>
