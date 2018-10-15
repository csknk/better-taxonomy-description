<?php
/**
 * @author  David Egan <david@carawebs.com>
 * @license https://opensource.org/licenses/gpl-2.0.php
 */
namespace Carawebs\BetterTaxonomy;

/**
 * Remove the existing description textarea on specific custom taxonomies
 */
class AmendFields {

  /**
   * Taxonomy screen IDs that should have the default description removed
   * @var array
   */
  private $taxonomy_screens;

  /**
   * Taxonomy slugs for taxonomies to amend
   * @var array
   */
  private $taxonomies;

  /**
   * @param array $taxonomies Slugs for taxonomies to amend
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
    if (!is_array($taxonomies)) {
        return;
        //$this->taxonomy_screens = [];
    }
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
   * Replace HTML filtering for output
   *
   * Reinstates the normal filters for term description on taxonomies that are not activated
   *
   * @return [type] [description]
   */
  public function conditionally_replace_html_filtering() {

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

  /**
   * Restrict allowed HTML tags on term description
   *
   * @see https://codex.wordpress.org/Function_Reference/wp_kses
   * @see https://core.trac.wordpress.org/browser/tags/4.5.3/src/wp-includes/kses.php#L0
   * @uses wp_kses()
   * @param  string $term_description Term description
   * @return string                   Filtered content with only allowed HTML elements
   */
  public function kill_scripts( $term_description ) {

    $allowed = [

      'a'           => [ 'href' => [], 'title' => [] ],
      'br'          => [],
      'em'          => [],
      'strong'      => [],
      'p'           => [],
      'blockquote'  => [],
      'hr'          => [],
      'h2'          => [],
      'h3'          => [],
      'h4'          => [],
      'ul'          => [],
      'li'          => [],
      'ol'          => []

    ];

    return wp_kses( $term_description, $allowed );

  }

}
