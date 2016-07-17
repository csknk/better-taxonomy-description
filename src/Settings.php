<?php
/**
 * @author  David Egan <david@carawebs.com>
 * @license https://opensource.org/licenses/gpl-2.0.php
 */
namespace Carawebs\BetterTaxonomy;

/**
 * Class for plugin settings
 */
class Settings {

  public function __construct( Config $config = NULL ) {

    $this->taxonomies = $config['taxonomy'];
    $this->option = CARAWEBS_BETTER_TAX_OPTION;

  }

  function add_admin_menu() {

    add_options_page(
      'Better Taxonomy Description',  // Page title tag
      'Taxonomy Description',         // Menu Title
      'manage_options',               // Capability
      'taxonomy_description',         // Menu slug (should be unique)
      [ $this, 'cw_options_page' ]    // Callback that outputs page content
    );

  }


  function setup_settings() {

    register_setting(
    'cw_better_tax',
    $this->option,//'carawebs_better_tax',
    [ $this, 'sanitize' ]
  );

    add_settings_section(
      'cw_cw_better_tax_section',
      NULL,                           // Section description
      [ $this, 'section_callback'],
      'cw_better_tax'
    );

    add_settings_field(
      'taxonomy',                                               // ID
      __( 'Select Taxonomy', 'better-taxonomy-description' ),   // Field Title
      [ $this, 'render_taxonomy_selector' ],                    // Render callback
      'cw_better_tax',                                          // Page
      'cw_cw_better_tax_section'                                // Section
    );

  }


  function taxonomy_selector() {

    $options = get_option( 'carawebs_better_tax' );
    ?>
    <input type='checkbox' name='carawebs_better_tax[cw_checkbox_field_0]' <?php checked( $options['cw_checkbox_field_0'], 1 ); ?> value='1'>
    <?php

  }

  /**
  * Render custom-post-type selector
  * @param  string $field options
  * @return string HTML markup for checkbox field
  */
  public function render_taxonomy_selector() {

    $taxonomies = get_taxonomies( NULL, 'objects');
    $disallowed = ['link_category', 'post_format', 'nav_menu'];

    ob_start();

    foreach ( $taxonomies  as $taxonomy ) {

      if( in_array( $taxonomy->name, $disallowed ) ) continue;

      $checked = NULL;
      $existing = ! empty( $this->taxonomies ) ? $this->taxonomies : NULL;
      if ( isset( $existing ) && is_array( $existing ) ) {

        $checked = in_array( $taxonomy->name, $existing ) ? "checked='checked'" : NULL;

      }

    echo "<input type='hidden' name='{$this->option}[taxonomy][]' value='0'>";
    echo "<label><input type='checkbox' name='{$this->option}[taxonomy][]' value='{$taxonomy->name}'$checked>&nbsp;$taxonomy->label</label><br>";

    }

    echo ! empty( $desc ) ? "<p class='description'>Description</p>" : NULL;

    echo ob_get_clean();

  }

  function section_callback(  ) {

    echo __( 'Choose taxonomies that you\'d like to apply enhanced taxonomy descriptions on.', 'better-taxonomy-description' );

  }


  function cw_options_page() {

    ?>
    <form action='options.php' method='post'>

      <h2>Taxonomy Description</h2>

      <?php
      settings_fields( 'cw_better_tax' );
      do_settings_sections( 'cw_better_tax' );
      submit_button();
      ?>

    </form>
    <?php

  }

  public function sanitize( $option ) {

    $option['taxonomy'] = array_slice( array_filter( $option['taxonomy'] ), 0 );
    $option['taxonomy'] = array_filter( $option['taxonomy'], function( $value ) {

      return wp_kses_post( trim( stripslashes( $value ) ) );

    });

    error_log(json_encode($option));

    return $option;

  }

}
