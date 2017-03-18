<?php

/**
 *
 * On the French Joomla! forum (http://forum.joomla.fr), Yann Gomiero (aka Daneel - https://www.joomla.fr/afuj/l-afuj/item/1010-yann-gomiero) has suggested this
 * script "log.php" to make very easy to connect on the Joomla Administration when you don't have anymore the login and password of a super admin.
 *
 * Usefull when, f.i., a client is contacting you "I've forgot my login/pwd, how to connect back in my Joomla admin ?"
 *
 * Credits : Yann Gomiero (see, in French, http://forum.joomla.fr/showthread.php?210994-hack%C3%A9-par&p=1073135&viewfull=1#post1073135)
 *
 * Note : The "official" (= recommended by the Joomla project) way is either to edit the configuration.php file or to go phpMyAdmin and
 * create a new user / reset an existing user.  More info on https://docs.joomla.org/How_do_you_recover_or_reset_your_admin_password%3F
 *
 **/

define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);

if (file_exists(dirname(__FILE__) . '/defines.php')) {
    include_once dirname(__FILE__) . '/defines.php';
}

if (!defined('_JDEFINES')) {
    define('JPATH_BASE', dirname(__FILE__));
    require_once JPATH_BASE.'/includes/defines.php';
}

require_once JPATH_BASE.'/includes/framework.php';
require_once JPATH_BASE.'/includes/helper.php';
require_once JPATH_BASE.'/includes/toolbar.php';

$app = JFactory::getApplication('administrator');
JPluginHelper::importPlugin('user');

$user = JUser::getInstance();
$db = JFactory::getDBO();

// Retrieve the first non blocked super admin
$q = 'SELECT u.* FROM `#__users` as u LEFT JOIN `#__user_usergroup_map` as ug ON u.id = ug.user_id WHERE `block` = 0 AND `activation` = 0 AND ug.group_id = 8 LIMIT 0,1';
$db->setQuery($q);

// Load the record
$user_tmp = $db->loadObject();

$user_tmp->guest = 0;
$user_tmp->isRoot = 1;

foreach ($user_tmp as $k => $v) {
    $user->set($k, $v);
}

// Instanciate a new session
$session = JFactory::getSession();
$session->set('user', $user);

// and connect to the backend
$app = JFactory::getApplication();
$app->checkSession();

// Now, self delete this script
if (unlink(__FILE__)) {
    $msg = 'The '.__FILE__.' has been successfully deleted.';
    $msgType = 'message';
} else {
    $msg = 'WARNING !!! Please remove the '.__FILE__.' manually, don\'t forget otherwise the security of your website is in danger.';
    $msgType = 'error';
}

// And display the message
$app->redirect(JUri::base(), $msg, $msgType);
