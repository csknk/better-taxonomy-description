<?php
/**
 * @author  David Egan <david@carawebs.com>
 * @license https://opensource.org/licenses/gpl-2.0.php
 *
 * Start the plugin
 */
namespace Carawebs\BetterTaxonomy;

require( dirname( __FILE__ ) . '/autoloader.php' );

// @TODO: Check to see if we can exit when doing AJAX - not sure!
add_action( 'plugins_loaded', function() {

    autoload();

    // Kick off WP CLI if using
    if( defined( 'WP_CLI' ) && WP_CLI ) {
      require_once( dirname( __FILE__ ) . '/WPCLI/Convert.php' );
      return;
    }

    $config         = new Config();
    $settings_page  = new Settings( $config );
    $description    = new TaxonomyDescription();
    $amend_fields   = new AmendFields( $config['taxonomy'] );

    // Controller class instantiates objects and attaches their methods to appropriate hooks.
    $controller = new Controller(
      $config,
      $settings_page,
      $description,
      $amend_fields
    );
    $controller->setupBackendActions();
    $controller->setupFrontendActions();

    // @TODO: Move the init hook into controller class for neatness
    //$controller->conditionallyReplaceFilters();
    add_action('init', [ $controller, 'conditionallyReplaceFilters' ], 99 );

    load_plugin_textdomain( 'better_taxonomy_description', false, dirname( plugin_basename(__FILE__) ) . '/lang' );

});
