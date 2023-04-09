<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$exs_layouts           = exs_get_feed_layout_options( true );
$exs_gaps              = exs_get_feed_layout_gap_options( true );
$exs_sidebar_positions = exs_get_sidebar_position_options( true );

//options for categories
//returning array of fields
return array(
	'layout'           => array(
		'type'        => 'select',
		'label'       => esc_html__( 'Layout', 'exs' ),
		'description' => esc_html__( 'Category layout', 'exs' ),
		'default'     => '',
		'choices'     => $exs_layouts,
	),
	'gap'              => array(
		'type'        => 'select',
		'label'       => esc_html__( 'Items gap (for grid layouts)', 'exs' ),
		'description' => esc_html__( 'Gap between elements in pixels', 'exs' ),
		'default'     => '',
		'choices'     => $exs_gaps,
	),
	'sidebar_position' => array(
		'type'        => 'select',
		'label'       => esc_html__( 'Sidebar position', 'exs' ),
		'description' => esc_html__( 'Sidebar position for category', 'exs' ),
		'default'     => '',
		'choices'     => $exs_sidebar_positions,
	),
);
