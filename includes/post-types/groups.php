<?php
/**
 * Post type groups setup, site hooks and filters.
 *
 * @package TenUpScaffold\Post_Type\Groups
 */

namespace TenUpScaffold\Post_Type\Groups;

/**
 * Hook into init action
 *
 * @since 0.1.0
 *
 * @uses add_action()
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	$n_opt_in = $n( 'opt_in' );

	// register the post type
	add_action( 'init', $n( 'register' ) );
}

/**
 * Register the group post type
 *
 * @since 0.1.0
 *
 * @uses register_post_type()
 *
 * @return void
 */
function register() {

	$group_labels = [
		'add_new_item'          => __( 'Add New Group', 'tenup-scaffold' ),
		'add_new'               => __( 'Add New', 'tenup-scaffold' ),
		'all_items'             => __( 'All Groups', 'tenup-scaffold' ),
		'archives'              => __( 'Group Archives', 'tenup-scaffold' ),
		'edit_item'             => __( 'Edit Group', 'tenup-scaffold' ),
		'featured_image'        => __( 'Featured Image', 'tenup-scaffold' ),
		'filter_items_list'     => __( 'Filter items list', 'tenup-scaffold' ),
		'insert_into_item'      => __( 'Insert into item', 'tenup-scaffold' ),
		'items_list_navigation' => __( 'Groups list navigation', 'tenup-scaffold' ),
		'items_list'            => __( 'Groups list', 'tenup-scaffold' ),
		'menu_name'             => __( 'Groups', 'tenup-scaffold' ),
		'name_admin_bar'        => __( 'Group', 'tenup-scaffold' ),
		'name'                  => _x( 'Groups', 'Group General Name', 'tenup-scaffold' ),
		'new_item'              => __( 'New Group', 'tenup-scaffold' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'tenup-scaffold' ),
		'not_found'             => __( 'Not found', 'tenup-scaffold' ),
		'parent_item_colon'     => __( 'Parent Group:', 'tenup-scaffold' ),
		'remove_featured_image' => __( 'Remove featured image', 'tenup-scaffold' ),
		'search_items'          => __( 'Search Group', 'tenup-scaffold' ),
		'set_featured_image'    => __( 'Set featured image', 'tenup-scaffold' ),
		'singular_name'         => _x( 'Group', 'Group Singular Name', 'tenup-scaffold' ),
		'update_item'           => __( 'Update Group', 'tenup-scaffold' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'tenup-scaffold' ),
		'use_featured_image'    => __( 'Use as featured image', 'tenup-scaffold' ),
		'view_item'             => __( 'View Group', 'tenup-scaffold' ),
	];
	$group_args = [
		'can_export'          => true,
		'capability_type'     => 'page',
		'exclude_from_search' => false,
		'has_archive'         => true,
		'hierarchical'        => false,
		'labels'              => $group_labels,
		'menu_icon'           => 'dashicons-groups',
		'public'              => true,
		'publicly_queryable'  => true,
		'rewrite'             => [
			'slug'       => 'Groups',
			'with_front' => false,
		],
		'show_in_admin_bar'   => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_rest'        => true,
		'show_ui'             => true,
		'supports'            => [ 'title', 'editor', 'excerpt', 'page-attributes', 'thumbnail' ],
		'taxonomies'          => [],
	];
	register_post_type( 'Group', $group_args );

}
