<?php
/*
Plugin Name: Better Taxonomy
Plugin URI: http://bitbucket.org/carawebs/better-taxonomy-description
Description: Replace textarea description field with WYSIWYG on select taxonomies
Version: 0.1
Author: David Egan
Author URI: http://davidegan.me
License: GPL2
*/

/*
GNU GENERAL PUBLIC LICENSE

Copyright (c) 2015 David Egan

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
/**
 * @author  David Egan <david@carawebs.com>
 * @license https://opensource.org/licenses/gpl-2.0.php
 */
namespace Carawebs\BetterTaxonomy;
//if ( ! is_admin() ) { return; }

/**
 * Define constants for this plugin
 */
define( 'CARAWEBS_BETTER_TAX_PATH', plugin_dir_path( __FILE__ ) );
define( 'CARAWEBS_BETTER_TAX_BASE_URL', plugins_url( NULL, __FILE__ ) );
define( 'CARAWEBS_BETTER_TAX_SLUG', 'carawebs_better_tax' );
define( 'CARAWEBS_BETTER_TAX_OPTION', 'carawebs_better_tax' );

/**
 * Load Composer autoload if available, otherwise register a simple autoload callback.
 *
 * @return void
 */
function autoload() {

  static $done;

  // Go ahead if $done == NULL or the class doesn't exist
  if ( ! $done && ! class_exists( 'Carawebs\OrganisePosts\Plugin', true ) ) {

    $done = true;

    file_exists( __DIR__.'/vendor/autoload.php' )
        ? require_once __DIR__.'/vendor/autoload.php'
        : spl_autoload_register( function ( $class ) {

            if (strpos($class, __NAMESPACE__) === 0) {

                $name = str_replace('\\', '/', substr($class, strlen(__NAMESPACE__)));

                require_once __DIR__."/src{$name}.php";

            }

        });

  }

}

function setup() {

  $config = new Config();
  $settings_page = new Settings( $config );

  add_action( 'admin_menu', [ $settings_page, 'cw_add_admin_menu' ] );
  add_action( 'admin_init', [ $settings_page, 'carawebs_better_tax_init' ] );

  $description = new Views\TaxonomyDescription();

  // Loop through the set taxonomies and connect up the hooks
  $taxonomies = $config['taxonomy'];
  foreach( $taxonomies as $taxonomy ) {

    add_action( $taxonomy . '_edit_form_fields', [ $description, 'description' ] );

  }

  //http://stackoverflow.com/questions/6285812/wordpress-apply-remove-filter-only-on-one-page
  // @TODO: http://stackoverflow.com/a/36608926

  $amend_fields = new RemoveOldField( $taxonomies );
  add_action( 'admin_head', [ $amend_fields, 'remove_default_category_description' ] );
  add_action( 'admin_init', [ $amend_fields, 'remove_html_filtering' ] );
  add_action( 'wp_head', [ $amend_fields, 'replace_html_filtering_for_output' ] );

}

add_action( 'plugins_loaded', function () {

    load_plugin_textdomain( 'better_taxonomy_description', false, dirname( plugin_basename(__FILE__) ) . '/lang' );

});

autoload();
setup();
