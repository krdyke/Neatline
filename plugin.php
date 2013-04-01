<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 cc=76; */

/**
 * Plugin runner.
 *
 * @package     omeka
 * @subpackage  neatline
 * @copyright   2012 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */


if (!defined('NL_DIR')) define('NL_DIR', dirname(__FILE__));

// Plugin class.
require_once NL_DIR . '/NeatlinePlugin.php';

// Application classes.
require_once NL_DIR . '/models/abstract/Record.php';
require_once NL_DIR . '/models/abstract/Styleset.php';
require_once NL_DIR . '/models/abstract/StylesetTable.php';
require_once NL_DIR . '/controllers/abstract/Rest.php';
require_once NL_DIR . '/jobs/ItemImporter.php';

// Helpers.
require_once NL_DIR . '/helpers/Assets.php';
require_once NL_DIR . '/helpers/Layers.php';
require_once NL_DIR . '/helpers/Plugins.php';
require_once NL_DIR . '/helpers/Views.php';
require_once NL_DIR . '/helpers/Styles.php';

// Forms.
require_once NL_DIR . '/forms/ExhibitForm.php';

Zend_Registry::set('layers', NL_DIR . '/layers.json');
Zend_Registry::set('fileIn', 'php://input');

$neatline = new NeatlinePlugin();
$neatline->setUp();
