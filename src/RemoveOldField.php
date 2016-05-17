<?php
/**
 * @author  David Egan <david@carawebs.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace PluginBoilerplate;

/**
 * Remove the existing description textarea on specific custom taxon
 */
class RemoveOldField {

  /**
   * An array of taxonomy screen IDs that should have the default description removed
   * @var array
   */
  private $taxonomy_screens;

  /**
   * @param array $taxonomies Array of taxonomy slugs for taxonomies that should have the default description removed
   */
  public function __construct( $taxonomies ) {

    $this->set_taxonomy_screens( $taxonomies );

  }

  /**
   * Set taxonomy screens
   *
   * @param array $taxonomies
   */
  private function set_taxonomy_screens( $taxonomies ) {

    // Process the array of taxonomy slugs to get an array of screen IDs
    $this->taxonomy_screens = array_map( function( $value ) {

      return 'edit-' . $value;

    }, $taxonomies );

  }

  /**
   * Callback that adds JS to remove default description textarea field
   *
   * @return string JS to be embedded in <head>
   */
  public function remove_default_category_description() {

    global $current_screen;
    if ( ! in_array( $current_screen->id, $this->taxonomy_screens ) ) { return; }

    ?>
        <script type="text/javascript">
        jQuery(function($) {
            $('textarea#description').closest('tr.form-field').remove();
        });
        </script>
    <?php

  }

}
