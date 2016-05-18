<?php
/*
Plugin Name: Better Taxonomy
Plugin URI: http://bitbucket.org/carawebs/better-taxonomy-description
Description: Replace textarea description field with WYSIWYG on select taxonomies
Version: 0.1
Author: David Egan
Author URI: http://davidegan.me
License: MIT
*/

/*
The MIT License (MIT)

Copyright (c) 2015 David Egan

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
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

$taxonomies = ['product-application', 'product-category' ];

function setup_wysiwyg( $taxonomies ) {

  class_exists( 'Carawebs\BetterTaxonomy\TaxonomyDescription' ) or require_once __DIR__ . '/src/Views/TaxonomyDescription.php';
  class_exists( 'Carawebs\BetterTaxonomy\RemoveOldField' ) or require_once __DIR__ . '/src/RemoveOldField.php';

  $description = new Views\TaxonomyDescription();

  foreach( $taxonomies as $taxonomy ) {

    add_action( $taxonomy . '_edit_form_fields', [ $description, 'description' ] );
    $description->remove_html_filtering();

  }

  add_action('admin_head', [ new RemoveOldField( $taxonomies ), 'remove_default_category_description' ] );

}

setup_wysiwyg( $taxonomies );
