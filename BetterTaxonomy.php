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
namespace Carawebs\BetterTaxonomy;

if ( ! is_admin() ) { return; }

$autoload = __DIR__ . '/vendor/autoload.php';

if ( file_exists( $autoload ) ) {

    require_once $autoload;

}

add_action( 'plugins_loaded', function () {

    load_plugin_textdomain( 'better_taxonomy_description', false, dirname( plugin_basename(__FILE__) ) . '/lang' );

});

function setup() {

  require_once __DIR__ . '/src/Settings.php';

  $settings_page = new Settings();

  add_action( 'admin_menu', [ $settings_page, 'cw_add_admin_menu' ] );
  add_action( 'admin_init', [ $settings_page, 'carawebs_tax_desc_init' ] );

}

$taxonomies = ['product-application', 'product-category' ];

function setup_wysiwyg( $taxonomies ) {

  class_exists( 'Carawebs\BetterTaxonomy\Views\TaxonomyDescription' ) or require_once __DIR__ . '/src/Views/TaxonomyDescription.php';
  class_exists( 'Carawebs\BetterTaxonomy\Views\RemoveOldField' ) or require_once __DIR__ . '/src/RemoveOldField.php';

  $description = new Views\TaxonomyDescription();

  foreach( $taxonomies as $taxonomy ) {

    add_action( $taxonomy . '_edit_form_fields', [ $description, 'description' ] );
    $description->remove_html_filtering();

  }

  add_action('admin_head', [ new RemoveOldField( $taxonomies ), 'remove_default_category_description' ] );

}

setup();
setup_wysiwyg( $taxonomies );
