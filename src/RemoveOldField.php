<?php
/**
 * @author  David Egan <david@carawebs.com>
 * @license https://opensource.org/licenses/gpl-2.0.php
 */
namespace Carawebs\BetterTaxonomy;

/**
 * Remove the existing description textarea on specific custom taxonomies
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

    $this->taxonomies = $taxonomies;

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

  /**
   * Removes filtering of term meta description in the admin area
   *
   * @return void
   */
  public function remove_html_filtering() {

    // global $current_screen;
    // if ( ! in_array( $current_screen->id, $this->taxonomy_screens ) ) { return; }

    remove_filter( 'pre_term_description', 'wp_filter_kses' );
    remove_filter( 'term_description', 'wp_kses_data' );

  }

  /**
   * Replace HTML filtering for output
   *
   * Reinstates the normal filters for term description on taxonomies that are not activated
   *
   * @return [type] [description]
   */
  public function replace_html_filtering_for_output() {

    $all_taxonomies = array_values( get_taxonomies() );

    // Filtered taxonomies need to have the normal filters reinstated for term description
    $filtered_taxonomies = array_diff( $all_taxonomies, $this->taxonomies );

    foreach( $filtered_taxonomies as $taxonomy ) {

      if( is_tax( $taxonomy ) ) {

        add_filter( 'pre_term_description', 'wp_filter_kses' );
        add_filter( 'term_description', 'wp_kses_data' );

      } elseif( 'post_tag' === $taxonomy && is_tag() ) {

        // The `is_tax()` conditional does not pick up 'post_tag'
        add_filter( 'pre_term_description', 'wp_filter_kses' );
        add_filter( 'term_description', 'wp_kses_data' );

      } elseif ( 'category' === $taxonomy && is_category() ) {

        // The `is_tax()` conditional does not pick up 'category'
        add_filter( 'pre_term_description', 'wp_filter_kses' );
        add_filter( 'term_description', 'wp_kses_data' );

      }

    }

  }

}
