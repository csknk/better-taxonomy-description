<?php
/**
 * @author  David Egan <david@carawebs.com>
 * @license https://opensource.org/licenses/gpl-2.0.php
 */
namespace Carawebs\BetterTaxonomy;

/**
 * The controller class - hooks things up.
 */
class Controller {

  private $config;

  private $taxonomies;

  private $settings;

  private $description;

  private $amend_fields;

  public function __construct( Config $config, Settings $settings, TaxonomyDescription $description, AmendFields $amend_fields ) {

    $this->config         = $config;
    $this->taxonomies     = $config['taxonomy'];
    $this->settings_page  = $settings;
    $this->description    = $description;
    $this->amend_fields   = $amend_fields;

  }

  /**
   * Set up hooks & filters that run in the admin
   *
   * @return void
   */
  public function setupBackendActions() {

    // Settings page for this plugin
    add_action( 'admin_menu', [ $this->settings_page, 'add_admin_menu' ] );
    add_action( 'admin_init', [ $this->settings_page, 'setup_settings' ] );

    if( empty( $this->taxonomies ) ) { return; }

    foreach( $this->taxonomies as $taxonomy ) {

      add_action( $taxonomy . '_edit_form_fields', [ $this->description, 'description' ] );

    }

    add_action( 'admin_head', [ $this->amend_fields, 'remove_default_category_description' ] );

    // The exisiting filter is too severe
    remove_filter( 'pre_term_description', 'wp_filter_kses' );

    // Allow the same HTML tags as for a regular post
    add_filter( 'pre_term_description', 'wp_kses_post' );

  }

  /**
   * Set up hooks & filters that run in the front end
   *
   * Escaping whilst maintaining video oembed: https://tomjn.com/2015/05/07/escaping-the-unsecure/
   *
   * @return void
   */
  public function setupFrontendActions() {

    remove_filter( 'term_description', 'wp_kses_data' );

    // Make sure the output is safe - limit the allowed HTML tags
    // Note the order, this must precede the embed filters
    add_filter( 'term_description', 'wp_kses_post', 7 );

    // Apply `the_content` filters to term description
    if ( isset( $GLOBALS['wp_embed'] ) ) {
      add_filter( 'term_description', array( $GLOBALS['wp_embed'], 'run_shortcode' ), 8 );
      add_filter( 'term_description', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );
    }

    add_filter( 'term_description', 'wptexturize' );
    add_filter( 'term_description', 'convert_smilies' );
    add_filter( 'term_description', 'convert_chars' );
    add_filter( 'term_description', 'wpautop' );
    add_filter( 'term_description', 'shortcode_unautop' );
    add_filter( 'term_description', 'do_shortcode', 11);

  }

  /**
   * Replace HTML filtering
   *
   * Reinstates the normal filters for term description on taxonomies that
   * have not been selected for better taxonomy descriptions
   *
   * @return void
   */
  public function conditionallyReplaceFilters() {

    $all_taxonomies = array_values( get_taxonomies() );

    // Filtered taxonomies: need to have the normal filters reinstated
    $filtered_taxonomies = array_diff( $all_taxonomies, $this->taxonomies );

    if( empty( $filtered_taxonomies ) ) return;

    add_action( 'init', function() use ( $filtered_taxonomies ) {

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

    });

  }

}
