<?php
/*
Plugin Name: Carawebs Better Taxonomy
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

// File path to this plugin
defined('CARAWEBS_BETTER_TAX_PATH') or define('CARAWEBS_BETTER_TAX_PATH', plugin_dir_path( __FILE__ ) );

// Base URL of this plugin
defined( 'CARAWEBS_BETTER_TAX_BASE_URL' ) or define( 'CARAWEBS_BETTER_TAX_BASE_URL', plugins_url( NULL, __FILE__ ) );

// The plugin slug
defined( 'CARAWEBS_BETTER_TAX_SLUG' ) or define( 'CARAWEBS_BETTER_TAX_SLUG', 'carawebs_better_tax' );

// The name that will be used as a key for the plugin options array
defined( 'CARAWEBS_BETTER_TAX_OPTION' ) or define( 'CARAWEBS_BETTER_TAX_OPTION', 'carawebs_better_tax' );

// Proceed with load only if minimum PHP requirements are met
if ( version_compare( PHP_VERSION, '5.4.0', '>=' ) ) {

  require ( dirname( __FILE__ ) . '/load.php' );

  return;

}

/**
 * Provide a notice if PHP version is incompatible and deactivate the plugin
 *
 * @return void
 */
function carawebs_old_php_notice() {

  ob_start();

  ?>
  <div class="error fade">
    <p><strong><?php echo __( 'Carawebs Better Taxonomy requires PHP 5.4 or later.', CARAWEBS_BETTER_TAX_SLUG ); ?></strong></p>
		<p><?php echo __( ' Please upgrade your server to the latest version of PHP – you may need to contact your web host.', CARAWEBS_BETTER_TAX_SLUG ); ?></p>
	</div>
  <?php

  echo ob_get_clean();

	deactivate_plugins( plugin_basename( __FILE__ ) );

}

add_action( 'admin_notices', 'carawebs_old_php_notice' );
