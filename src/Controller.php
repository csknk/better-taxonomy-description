<?php

namespace Carawebs\BetterTaxonomy;

/**
 * The controller class - hooks things up.
 */
class Controller {

  public function __construct( Config $config, Settings $settings, TaxonomyDescription $description, $amend_fields ) {

    $this->config = $config;
    $this->settings_page = $settings;
    $this->description = $description;
    $this->amend_fields = $amend_fields;

  }

  public function setupFrontendActions() {

    //add_action( 'init', [ $this->amend_fields, 'remove_html_filtering' ] );
    //add_action( 'wp_head', [ $amend_fields, 'replace_html_filtering_for_output' ] );
    //add_action( 'init', [ $this->amend_fields, 'remove_html_filtering' ] );

  }

//   add_action( 'admin_head', [ $amend_fields, 'remove_default_category_description' ] );
//   add_action( 'init', [ $amend_fields, 'remove_html_filtering' ] );

  public function setupBackendActions() {

    add_action( 'admin_menu', [ $this->settings_page, 'cw_add_admin_menu' ] );
    add_action( 'admin_init', [ $this->settings_page, 'carawebs_better_tax_init' ] );

    $taxonomies = $this->config['taxonomy'];
    if( empty( $taxonomies ) ) { return; }

    // Loop through the set taxonomies and connect up the hooks
    foreach( $taxonomies as $taxonomy ) {

      add_action( $taxonomy . '_edit_form_fields', [ $this->description, 'description' ] );

    }

    //$amend_fields = new RemoveOldField( $taxonomies );
    add_action( 'admin_head', [ $this->amend_fields, 'remove_default_category_description' ] );
    add_action( 'init', [ $this->amend_fields, 'remove_html_filtering' ] );

  }

}
