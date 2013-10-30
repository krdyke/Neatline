
/* vim: set expandtab tabstop=2 shiftwidth=2 softtabstop=2 cc=80; */

/**
 * @package     omeka
 * @subpackage  neatline
 * @copyright   2012 Rector and Board of Visitors, University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html
 */

Neatline.module('Editor', { startWithParent: false,
  define: function(Editor) {


  Editor.ID = 'EDITOR';


  /**
   * Start the editor before Neatline.
   */
  Neatline.on('initialize:before', function() {
    Editor.start();
  });


  /**
   * Start recording history once Neatline is running.
   */
  Neatline.on('initialize:after', function() {
    Backbone.history.start();
  });


  Editor.addInitializer(function() {


    Editor.__view = new Editor.View({ el: 'body' });


    /**
     * Display a list of views in the editor container.
     *
     * @param {Array} views: The views, in display order.
     */
    var display = function(views) {

      // Clear the editor container.
      Editor.__view.__ui.editor.children().detach();

      // Show each of the views.
      _.each(views, function(v) {
        Neatline.execute(v+':display', Editor.__view.__ui.editor);
      });

    };
    Neatline.commands.setHandler(Editor.ID+':display', display);


    /**
     * Flash a success notification.
     *
     * @param {String} message: The message.
     */
    var notifySuccess = function(message) {
      Editor.__view.notifySuccess(message);
    };
    Neatline.commands.setHandler(Editor.ID+':notifySuccess', notifySuccess);


    /**
     * Flash an error notification.
     *
     * @param {String} message: The message.
     */
    var notifyError = function(message) {
      Editor.__view.notifyError(message);
    };
    Neatline.commands.setHandler(Editor.ID+':notifyError', notifyError);


    /**
     * Return the editor container div.
     *
     * @return {Object}: The container.
     */
    var getContainer = function() {
      return Editor.__view.__ui.editor;
    };
    Neatline.reqres.setHandler(Editor.ID+':getContainer', getContainer);


    /**
     * Update the route hash without adding to the history.
     *
     * @param {String} route: The new route.
     */
    var setRoute = function(route) {
      Backbone.history.navigate(route, { replace: true });
    };
    Neatline.commands.setHandler(Editor.ID+':setRoute', setRoute);


  });


}});
