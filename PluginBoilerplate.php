<?php
/*
Plugin Name: PluginBoilerplate
Plugin URI: http://bitbucket.org/carawebs/plugin-boilerplate
Description: Add a button on Editor toolbar that when clicked render all shortcodes to actual HTML.
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
namespace PluginBoilerplate;

if (! is_admin()) {
    return;
}

$autoload = __DIR__.'/vendor/autoload.php';

if (file_exists($autoload)) {

    require_once $autoload;

}

add_action( 'plugins_loaded', function () {

    load_plugin_textdomain( 'plugin_boilerplate', false, dirname( plugin_basename(__FILE__) ).'/lang' );

});

$taxonomies = ['product-application', 'product-category' ];

function setup_wysiwyg( $taxonomies ) {

  class_exists( 'PluginBoilerplate\TaxonomyDescription' ) or require_once __DIR__ . '/src/TaxonomyDescription.php';

  foreach( $taxonomies as $taxonomy ) {

    add_action( $taxonomy . '_edit_form_fields', [ new TaxonomyDescription(), 'description' ] );

  }

  TaxonomyDescription::remove_html_filtering();

}

setup_wysiwyg( $taxonomies );

// if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
//
//     class_exists( 'PluginBoilerplate\FormData' ) or require_once __DIR__ . '/src/FormData.php';
//
//     add_action( 'wp_ajax_plugin_boilerplate-render', [ new Renderer( new FormData() ), 'render'] );
//
// } elseif ( in_array($GLOBALS['pagenow'], ['post.php', 'post-new.php'], true) ) {
//
//     class_exists( 'PluginBoilerplate\AdminForm' ) or require_once __DIR__ . '/src/AdminForm.php';
//     class_exists( 'PluginBoilerplate\FormData' ) or require_once __DIR__ . '/src/FormData.php';
//     class_exists( 'PluginBoilerplate\PostProvider' ) or require_once __DIR__ . '/src/PostProvider.php';
//
//     add_action( 'admin_init', [ new AdminForm( new FormData( ( new PostProvider() )->init() ) ), 'setup'] );
//
// }
