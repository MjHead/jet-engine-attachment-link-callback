<?php
/**
 * Plugin Name: JetEngine - Get attachment file link by ID
 * Plugin URI:  #
 * Description: Adds new callback to Dynamic Field widget, which allows to convert attachment file ID into attchment file link.
 * Version:     1.0.0
 * Author:      Crocoblock
 * Author URI:  https://crocoblock.com/
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

add_filter( 'jet-engine/listings/allowed-callbacks', 'jet_engine_add_attachment_link_callback', 10, 2 );

function jet_engine_add_attachment_link_callback( $callbacks ) {
	$callbacks['jet_engine_get_attachment_file_link'] = 'Get attachment file link by ID';
	return $callbacks;
}

function jet_engine_get_attachment_file_link( $attachment_id ) {

	$url  = wp_get_attachment_url( $attachment_id );
	$name = basename( $url );

	return sprintf( '<a href="%1$s">%2$s</a>', $url, $name );

}
