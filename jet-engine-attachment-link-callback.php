<?php
/**
 * Plugin Name: JetEngine - Get attachment file link by ID
 * Plugin URI: #
 * Description: Adds new callback to Dynamic Field widget, which allows to convert attachment file ID into attchment file link.
 * Version:     1.1.1
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
add_filter( 'jet-engine/listing/dynamic-field/callback-args', 'jet_engine_add_attachment_link_callback_args', 10, 3 );
add_action( 'jet-engine/listing/dynamic-field/callback-controls', 'jet_engine_add_attachment_link_callback_controls' );

function jet_engine_add_attachment_link_callback( $callbacks ) {
	$callbacks['jet_engine_get_attachment_file_link'] = 'Get attachment file link by ID';
	return $callbacks;
}

function jet_engine_get_attachment_file_link( $attachment_id, $display_name = 'file_name', $label = '' ) {

	$url = wp_get_attachment_url( $attachment_id );

	switch ( $display_name ) {
		case 'post_title':
			$name = get_the_title( $attachment_id );
			break;

		case 'current_post_title':
			$name = get_the_title( get_the_ID() );
			break;

		case 'parent_post_title':
			$parent_id = wp_get_post_parent_id( $attachment_id );

			if ( ! $parent_id ) {
				$parent_id = get_the_ID();
			}

			$name = get_the_title( $parent_id );
			break;

		case 'custom':
			$name = $label;
			break;

		default:
			$name = basename( $url );
			break;
	}

	return sprintf( '<a href="%1$s">%2$s</a>', $url, $name );

}

function jet_engine_add_attachment_link_callback_args( $args, $callback, $settings = array() ) {

	if ( 'jet_engine_get_attachment_file_link' === $callback ) {
		$args[] = isset( $settings['jet_attachment_name'] ) ? $settings['jet_attachment_name'] : 'file_name';
		$args[] = isset( $settings['jet_attachment_label'] ) ? $settings['jet_attachment_label'] : '';
	}

	return $args;

}

function jet_engine_add_attachment_link_callback_controls( $widget ) {

	$widget->add_control(
		'jet_attachment_name',
		array(
			'label'       => esc_html__( 'Display name', 'jet-engine' ),
			'type'        => \Elementor\Controls_Manager::SELECT,
			'label_block' => true,
			'description' => esc_html__( 'Select attachment name format to display', 'jet-engine' ),
			'default'     => 'file_name',
			'options'     => array(
				'file_name'          => 'File name',
				'post_title'         => 'Attachment post title',
				'current_post_title' => 'Current post title',
				'parent_post_title'  => 'Parent post title',
				'custom'             => 'Custom',
			),
			'condition'   => array(
				'dynamic_field_filter' => 'yes',
				'filter_callback'      => array( 'jet_engine_get_attachment_file_link' ),
			),
		)
	);

	$widget->add_control(
		'jet_attachment_label',
		array(
			'label'       => esc_html__( 'Custom label', 'jet-engine' ),
			'type'        => \Elementor\Controls_Manager::TEXT,
			'label_block' => true,
			'description' => esc_html__( 'Set custom text for the attachment link', 'jet-engine' ),
			'default'     => '',
			'condition'   => array(
				'jet_attachment_name'  => 'custom',
				'dynamic_field_filter' => 'yes',
				'filter_callback'      => array( 'jet_engine_get_attachment_file_link' ),
			),
		)
	);

}
