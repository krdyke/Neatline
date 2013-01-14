<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 cc=76; */

/**
 * Miscellaneous helpers.
 *
 * @package     omeka
 * @subpackage  neatline
 * @copyright   2012 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */


/**
 * Include the static files for the Neatline.
 */
function neatline_queueNeatlineAssets()
{
    neatline_queueGoogleMapsApi();
    queue_css_file('payloads/neatline');
    queue_js_file('payloads/neatline');
    queue_js_file('bootstrap');
}


/**
 * Include the static files for the editor.
 */
function neatline_queueEditorAssets()
{
    queue_css_file('payloads/editor');
    queue_js_file('payloads/editor');
    queue_js_file('bootstrap');
}


/**
 * Include the Google Maps API.
 */
function neatline_queueGoogleMapsApi()
{
    $url = 'http://maps.google.com/maps/api/js?v=3.8&sensor=false';
    $headScript = get_view()->headScript();
    $headScript->appendScript('','text/javascript',array('src'=>$url));
}


/**
 * Construct exhibit globals.
 *
 * @param NeatlineExhibit $exhibit The exhibit.
 * @return string JSON exhibit defaults.
 */
function neatline_exhibitGlobals($exhibit)
{
    return json_encode(array(
        'id'    => $exhibit->id,
        'api'   => array(
            'records'   => public_url('neatline/records/'.$exhibit->id),
            'record'    => public_url('neatline/record')
        ),
        'map'   => array(
            'focus'     => $exhibit->map_focus,
            'zoom'      => $exhibit->map_zoom
        )
    ));
}


/**
 * Gather all taggable styles exposed via the `neatline_links` filter.
 *
 * @return array The array of column name => label.
 */
function neatline_getStyles()
{
  return apply_filters('neatline_styles', array());
}


/**
 * Gather the column names for all taggable styles.
 *
 * @return array The array of column names.
 */
function neatline_getStyleCols()
{
  return array_keys(neatline_getStyles());
}


/**
 * Return specific field for a neatline record.
 *
 * @param string $fieldname The model attribute name being requested.
 * @param array $options An array of options.
 * @param Omeka_Record $neatline|null The exhibit.
 * @return string The field value.
 */
function neatline($fieldname, $options = array(), $neatline = null)
{

    // Get the exhibit and raw field value.
    $neatline = $neatline ? $neatline : get_current_neatline();
    $fieldname = strtolower($fieldname);
    $text = $neatline->$fieldname;

    // Truncate if snippet.
    if(isset($options['snippet'])) {
        $text = nls2p(snippet($text, 0, (int)$options['snippet']));
    }

    return $text;

}


/**
 * Returns the current neatline.
 *
 * @return NeatlineExhibit|null
 */
function get_current_neatline()
{
    return get_view()->neatline_exhibit;
}


/**
 * Determine whether there are any neatlines to loop on the view.
 *
 * @return boolean
 */
function has_neatlines_for_loop()
{
    $view = get_view();
    return ($view->neatline_exhibits and count($view->neatline_exhibits));
}


/**
 * Returns the total number of exhibits in the database.
 *
 * @return integer
 */
function total_neatlines()
{
    return get_db()->getTable('NeatlineExhibits')->count();
}


/**
 * Returns a link to a Neatline exhibit.
 *
 * @param NeatlineExhibit|null $exhibit The exhibit record.
 * @param string $text HTML for the text of the link.
 * @param array $props Array of properties for the element.
 * @param string $action The action for the link. Default is 'show'.
 * @return string The HTML link.
 */
function link_to_neatline(
    $exhibit  = null,
    $text     = null,
    $props    = array(),
    $action   = 'show',
    $public   = true)
{

    // Get the exhibit, form the link text.
    $exhibit = $exhibit ? $exhibit : get_current_neatline();
    $text = $text ? $text : strip_formatting(neatline('title', $exhibit));

    // Form the identified (id or slug).
    if ($action == 'show') { $slug = $exhibit->slug; }
    else { $slug = $exhibit->id; }

    // Form the route and link tag.
    $route = 'neatline/' . $action . '/' . $slug;
    $uri = $public ? public_url($route) : url($route);
    $props['href'] = $uri;

    return '<a ' . tag_attributes($props) . '>' . $text . '</a>';

}


/**
 * Returns the number of records used in a given Neatline.
 *
 * @param NeatlineExhibit|null $neatline The exhibit record.
 * @return integer
 */
function total_records_for_neatline($neatline = null)
{
    $neatline = $neatline ? $neatline : get_current_neatline();
    return (int)$neatline->getNumberOfRecords();
}
