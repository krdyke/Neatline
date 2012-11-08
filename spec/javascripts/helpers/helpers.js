/**
 * Testing helpers.
 *
 * @package     omeka
 * @subpackage  neatline
 * @copyright   2012 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */


_t = {};


// --------------------
// Application loaders.
// --------------------

/*
 * Load neatline application.
 */
_t.loadNeatline = function() {

  // Restart components.
  Neatline.Controllers.Exhibit.init();
  Neatline.Controllers.Map.init();

  // Shortcut components
  _t.map = Neatline.Controllers.Map.Map;

};