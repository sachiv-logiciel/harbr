<?php

// ensure EXT is defined
if ( ! defined( 'EXT' ) ) {
	define( 'EXT', '.php' );
}

#
# See: wpgrade-config.php -> include-paths for additional theme specific
# function and class includes
#

// ensure REQUEST_PROTOCOL is defined
if ( ! defined('REQUEST_PROTOCOL')) {
	if (is_ssl()) {
		define( 'REQUEST_PROTOCOL', 'https:' );
	} else {
		define( 'REQUEST_PROTOCOL', 'http:' );
	}
}

// Theme specific settings
// -----------------------

// add theme support for post formats
// child themes note: use the after_setup_theme hook with a callback
// right now no post formats
//$formats = array( 'video', 'audio', 'gallery', 'image', 'quote', 'link', 'chat', 'aside', );
//add_theme_support( 'post-formats', $formats );

// Initialize system core
// ----------------------

require_once 'wpgrade-core/bootstrap' . EXT;

#
# Please perform any initialization via options in wpgrade-config and
# calls in wpgrade-core/bootstrap. Required for testing.
#

// Custom post type for blog Section
// ---------------------------------

register_post_type( 'blog-post',
	array(
		'labels' => array(
				'name' => __( 'Blog' ),
				'singular_name' => __( 'blog-post' ),
			),
		'public' => true,
		'has_archive' => true,
		'rewrite' => array('slug' => 'blog-post'),
		'supports' => array(
			'title',
			'author',
			'excerpt',
			'editor',
			'thumbnail',
			'revisions',
			'custom-fields',
			'comments'
			),
	)
);

//

function create_portfolio_taxonomies() {
  $labels = array(
    'name'              => _x( 'Categories', 'taxonomy general name' ),
    'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Categories' ),
    'all_items'         => __( 'All Categories' ),
    'parent_item'       => __( 'Parent Category' ),
    'parent_item_colon' => __( 'Parent Category:' ),
    'edit_item'         => __( 'Edit Category' ),
    'update_item'       => __( 'Update Category' ),
    'add_new_item'      => __( 'Add New Category' ),
    'new_item_name'     => __( 'New Category Name' ),
    'menu_name'         => __( 'Categories' ),
    );

  $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'categories' ),
        );

  register_taxonomy( 'portfolio_categories', array( 'blog-post' ), $args );
}
add_action( 'init', 'create_portfolio_taxonomies', 0 );
