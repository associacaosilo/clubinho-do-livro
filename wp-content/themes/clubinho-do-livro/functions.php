<?php
//Enqueue scripts

function cdl_enqueue_scripts() {

	$parent_style = 'twentytwenty-style';

	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/assets/css/editor-style-block.css' );
	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/assets/css/editor-style-classic.css' );
	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( $parent_style ),
		wp_get_theme()->get('Version')
	);

}

add_action( 'wp_enqueue_scripts', 'cdl_enqueue_scripts' );

//Register Book custom post type
function cdl_book_cpt() {
	$labels = array(
		'name'                  => _x( 'Book', 'Post type general name', 'cdl' ),
		'singular_name'         => _x( 'Book', 'Post type singular name', 'cdl' ),
		'menu_name'             => _x( 'Book', 'Admin Menu text', 'cdl' ),
		'name_admin_bar'        => _x( 'Book', 'Add New on Toolbar', 'cdl' ),
		'add_new'               => __( 'Add New', 'cdl' ),
		'add_new_item'          => __( 'Add New Book', 'cdl' ),
		'new_item'              => __( 'New Book', 'cdl' ),
		'edit_item'             => __( 'Edit Book', 'cdl' ),
		'view_item'             => __( 'View Book', 'cdl' ),
		'all_items'             => __( 'All Books', 'cdl' ),
		'search_items'          => __( 'Search Book', 'cdl' ),
		'not_found'             => __( 'No books found.', 'cdl' ),
		'not_found_in_trash'    => __( 'No books found in Trash.', 'cdl' ),
		'featured_image'        => _x( 'Book Cover', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'cdl' ),
		'set_featured_image'    => _x( 'Set book cover', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'cdl' ),
		'remove_featured_image' => _x( 'Remove book cover', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'cdl' ),
		'use_featured_image'    => _x( 'Use as book cover', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'cdl' ),
		'archives'              => _x( 'Library', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'cdl' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'has_archive'		 => true,
		'rewrite'            => array( 'slug' => __( 'library', 'cdl' ) ),
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'comments', 'revisions', 'excerpt', 'thumbnail' ),
		'menu_icon'          => 'dashicons-book',
	);

	register_post_type( 'book', $args );
}
add_action('init', 'cdl_book_cpt');

//Flush rewrite rules when activating theme
function cdl_rewrite_flush() {

	cdl_book_cpt();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'cdl_rewrite_flush' );

function cdl_book_archive_title( $title )
{
	if ( is_post_type_archive('book') ) {
		$title = __( 'Library', 'cdl' );
	}

	return $title;
}
add_filter( 'pre_get_document_title', 'cdl_book_archive_title' );

// create two taxonomies, genres and writers for the post type "book"
function cdl_create_book_taxonomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Proficiency', 'taxonomy general name', 'cdl' ),
		'singular_name'     => _x( 'Proficiency', 'taxonomy singular name', 'cdl' ),
		'search_items'      => __( 'Search Proficiency', 'cdl' ),
		'all_items'         => __( 'All Proficiency', 'cdl' ),
		'parent_item'       => __( 'Parent Proficiency', 'cdl' ),
		'parent_item_colon' => __( 'Parent Proficiency:', 'cdl' ),
		'edit_item'         => __( 'Edit Proficiency', 'cdl' ),
		'update_item'       => __( 'Update Proficiency', 'cdl' ),
		'add_new_item'      => __( 'Add New Proficiency', 'cdl' ),
		'new_item_name'     => __( 'New Proficiency Name', 'cdl' ),
		'menu_name'         => __( 'Proficiency', 'cdl' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => __( 'proficiency', 'cdl' ) ),
	);

	register_taxonomy( 'proficiency', array( 'book' ), $args );

	// Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name'                       => _x( 'Genre', 'taxonomy general name', 'cdl' ),
		'singular_name'              => _x( 'Genre', 'taxonomy singular name', 'cdl' ),
		'search_items'               => __( 'Search Genre', 'cdl' ),
		'popular_items'              => __( 'Popular Genre', 'cdl' ),
		'all_items'                  => __( 'All Genre', 'cdl' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Genre', 'cdl' ),
		'update_item'                => __( 'Update Genre', 'cdl' ),
		'add_new_item'               => __( 'Add New Genre', 'cdl' ),
		'new_item_name'              => __( 'New Genre Name', 'cdl' ),
		'separate_items_with_commas' => __( 'Separate genres with commas', 'cdl' ),
		'add_or_remove_items'        => __( 'Add or remove genres', 'cdl' ),
		'choose_from_most_used'      => __( 'Choose from the most used genres', 'cdl' ),
		'not_found'                  => __( 'No genres found.', 'cdl' ),
		'menu_name'                  => __( 'Genre', 'cdl' ),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => __( 'genre', 'cdl' ) ),
	);

	register_taxonomy( 'genre', 'book', $args );
}
// hook into the init action and call create_book_taxonomies when it fires
add_action( 'init', 'cdl_create_book_taxonomies', 0 );