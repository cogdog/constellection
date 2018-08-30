<?php

# -----------------------------------------------------------------
# Enqueue Scipts and Styles
# -----------------------------------------------------------------

add_action('wp_enqueue_scripts', 'add_constellection_scripts');

function add_constellection_scripts() {	 
    $parent_style = 'constellection_style'; 
    
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
    
    
    // for constellation single items
     if ( is_singular( 'constellation' )  ) { 
 		
    	// load d3js
		wp_register_script( 'jquery.d3js' , get_stylesheet_directory_uri() . '/js/d3.v4.min.js', '', '', false );
		wp_enqueue_script( 'jquery.d3js' );
	 }
}	 

function constellection_enqueue_add_star_scripts() {

	// Build in tag auto complete script
	wp_enqueue_script( 'suggest' );

    // custom jquery for the add star form
	wp_register_script( 'add_star_js' , get_stylesheet_directory_uri() . '/js/jquery.add-star.js', array( 'jquery' ), '1.0', TRUE );
	wp_enqueue_script( 'add_star_js' );

}

function constellection_enqueue_add_constellation_scripts() {

	// Build in tag auto complete script
	wp_enqueue_script( 'suggest' );

    // custom jquery for the add star form
	wp_register_script( 'add_constellation_js' , get_stylesheet_directory_uri() . '/js/jquery.add-constellation.js', array( 'jquery' ), '1.0', TRUE );
	wp_enqueue_script( 'add_constellation_js' );

}




# -----------------------------------------------------------------
# Custom Post Types and Taxonomy
# -----------------------------------------------------------------

add_action( 'init', 'constellection_register_tax' );
add_action( 'init', 'constellection_register_post_types', 11 );
add_action( 'post_updated_messages', 'constellection_updated_star_messages' );
add_action( 'post_updated_messages', 'constellection_updated_constellation_messages' );
add_filter( 'manage_star-item_posts_columns', 'constellection_star_admin_columns' );
add_filter( 'manage_constellation_posts_columns', 'constellection_constellation_admin_columns' );
add_action( 'after_switch_theme', 'constellection_flush_rules_on_switch' );

function constellection_register_post_types() {

		register_post_type( 'star-item', array(
			'description' => __( 'Resources that make up the constellations', 'rebalance' ),
			'labels' => array(
				'name'                  => esc_html__( 'Star Items',                   'rebalance' ),
				'singular_name'         => esc_html__( 'Star Item',                    'rebalance' ),
				'menu_name'             => esc_html__( 'Star Items',                  'rebalance' ),
				'all_items'             => esc_html__( 'All Star Items',               'rebalance' ),
				'add_new'               => esc_html__( 'Add New',                    'rebalance' ),
				'add_new_item'          => esc_html__( 'Add New Star Item',            'rebalance' ),
				'edit_item'             => esc_html__( 'Edit Star Item',               'rebalance' ),
				'new_item'              => esc_html__( 'New Star Item',                'rebalance' ),
				'view_item'             => esc_html__( 'View Star Item',               'rebalance' ),
				'search_items'          => esc_html__( 'Search Star Items',            'rebalance' ),
				'not_found'             => esc_html__( 'No Star Items found',          'rebalance' ),
				'not_found_in_trash'    => esc_html__( 'No Star Items found in Trash', 'rebalance' ),
				'filter_items_list'     => esc_html__( 'Filter star item list',       'rebalance' ),
				'items_list_navigation' => esc_html__( 'Star Item list navigation',    'rebalance' ),
				'items_list'            => esc_html__( 'Star items list',              'rebalance' ),
			),
			'supports' => array(
				'title',
				'editor',
				'comments',
				'custom-fields',
				'revisions',
				'excerpt',
			),
			'rewrite' => array(
				'slug'       => 'star',
				'with_front' => false,
				'feeds'      => true,
				'pages'      => true,
			),
			'public'          => true,
			'show_ui'         => true,
			'menu_position'   => 5,                    // below Pages
			'menu_icon'       => 'dashicons-star-empty', // 3.8+ dashicon option
			'map_meta_cap'    => true,
			'taxonomies'      => array( 'star-type', 'star-tag' ),
			'has_archive'     => true,
			'query_var'       => 'star',
			'show_in_rest'    => true,
		) );
		
		register_post_type( 'constellation', array(
			'description' => __( 'Each a collection of star items', 'rebalance' ),
			'labels' => array(
				'name'                  => esc_html__( 'Constellations',                   'rebalance' ),
				'singular_name'         => esc_html__( 'Constellation',                    'rebalance' ),
				'menu_name'             => esc_html__( 'Constellations',                  'rebalance' ),
				'all_items'             => esc_html__( 'All Constellations',               'rebalance' ),
				'add_new'               => esc_html__( 'Add New',                    'rebalance' ),
				'add_new_item'          => esc_html__( 'Add New Constellation',            'rebalance' ),
				'edit_item'             => esc_html__( 'Edit Constellation',               'rebalance' ),
				'new_item'              => esc_html__( 'New Constellation',                'rebalance' ),
				'view_item'             => esc_html__( 'View Constellation',               'rebalance' ),
				'search_items'          => esc_html__( 'Search Constellations',            'rebalance' ),
				'not_found'             => esc_html__( 'No Constellations found',          'rebalance' ),
				'not_found_in_trash'    => esc_html__( 'No Constellations found in Trash', 'rebalance' ),
				'filter_items_list'     => esc_html__( 'Filter constellation list',       'rebalance' ),
				'items_list_navigation' => esc_html__( 'Constellation list navigation',    'rebalance' ),
				'items_list'            => esc_html__( 'Constellations list',              'rebalance' ),
			),
			'supports' => array(
				'title',
				'editor',
				'thumbnail',
				'comments',
				'custom-fields',
				'revisions',
				'excerpt',
			),
			'rewrite' => array(
				'slug'       => 'constellation',
				'with_front' => false,
				'feeds'      => true,
				'pages'      => true,
			),
			'public'          => true,
			'show_ui'         => true,
			'menu_position'   => 5,                    // below Pages
			'menu_icon'       => 'dashicons-chart-line', // 3.8+ dashicon option
			'map_meta_cap'    => true,
			'taxonomies'      => array( 'constellation-cat', 'constellation-tag' ),
			'has_archive'     => true,
			'query_var'       => 'constellation',
			'show_in_rest'    => true,
		) );

}


function constellection_register_tax() {
	register_taxonomy( 'star-type', 'star-item', array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'                  => esc_html__( 'Star Item Types',                 'rebalance' ),
			'singular_name'         => esc_html__( 'Star Item Type',                  'rebalance' ),
			'menu_name'             => esc_html__( 'Star Item Types',                 'rebalance' ),
			'all_items'             => esc_html__( 'All  Types',             'rebalance' ),
			'edit_item'             => esc_html__( 'Edit Star Item Type',             'rebalance' ),
			'view_item'             => esc_html__( 'View Star Item Type',             'rebalance' ),
			'update_item'           => esc_html__( 'Update Star Item Type',           'rebalance' ),
			'add_new_item'          => esc_html__( 'Add New Star Item Type',          'rebalance' ),
			'new_item_name'         => esc_html__( 'New Star Item Type Name',         'rebalance' ),
			'parent_item'           => esc_html__( 'Parent Star Item Type',           'rebalance' ),
			'parent_item_colon'     => esc_html__( 'Parent Star Item Type:',          'rebalance' ),
			'search_items'          => esc_html__( 'Search Star Item Types',          'rebalance' ),
			'items_list_navigation' => esc_html__( 'Star Item  type list navigation',  'rebalance' ),
			'items_list'            => esc_html__( 'Star Item  type list',             'rebalance' ),
			'back_to_items'         => esc_html__( '&larr; Back to Star Item Types' ,  'rebalance' ),
		),
		'public'            => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'star-type' ),
	) );
	
	register_taxonomy( 'star-media', 'star-item', array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'                  => esc_html__( 'Star Media',                 'rebalance' ),
			'singular_name'         => esc_html__( 'Star Medium',                  'rebalance' ),
			'menu_name'             => esc_html__( 'Star Media',                 'rebalance' ),
			'all_items'             => esc_html__( 'All Media',             'rebalance' ),
			'edit_item'             => esc_html__( 'Edit Star Medium',             'rebalance' ),
			'view_item'             => esc_html__( 'View Star Medium',             'rebalance' ),
			'update_item'           => esc_html__( 'Update Star Medium',           'rebalance' ),
			'add_new_item'          => esc_html__( 'Add New Star Medium',          'rebalance' ),
			'new_item_name'         => esc_html__( 'New Star Medium Name',         'rebalance' ),
			'parent_item'           => esc_html__( 'Parent Star Medium',           'rebalance' ),
			'parent_item_colon'     => esc_html__( 'Parent Star Medium:',          'rebalance' ),
			'search_items'          => esc_html__( 'Search Star Media',          'rebalance' ),
			'items_list_navigation' => esc_html__( 'Star Media list navigation',  'rebalance' ),
			'items_list'            => esc_html__( 'Star Media list',             'rebalance' ),
			'back_to_items'         => esc_html__( '&larr; Back to Star Media' ,  'rebalance' ),
		),
		'public'            => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'star-media' ),
	) );


	register_taxonomy( 'star-tag', 'star-item', array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'                       => esc_html__( 'Star Item Tags',                   'rebalance' ),
			'singular_name'              => esc_html__( 'Star Item Tag',                    'rebalance' ),
			'menu_name'                  => esc_html__( 'Star Item Tags',                   'rebalance' ),
			'all_items'                  => esc_html__( 'All Tags',               'rebalance' ),
			'edit_item'                  => esc_html__( 'Edit Star Item Tag',               'rebalance' ),
			'view_item'                  => esc_html__( 'View Star Item Tag',               'rebalance' ),
			'update_item'                => esc_html__( 'Update Star Item Tag',             'rebalance' ),
			'add_new_item'               => esc_html__( 'Add New Star Item Tag',            'rebalance' ),
			'new_item_name'              => esc_html__( 'New Star Item Tag Name',           'rebalance' ),
			'search_items'               => esc_html__( 'Search Star Item Tags',            'rebalance' ),
			'popular_items'              => esc_html__( 'Popular Star Item Tags',           'rebalance' ),
			'separate_items_with_commas' => esc_html__( 'Separate star item tags with commas',      'rebalance' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove star item tags',             'rebalance' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used star item tags', 'rebalance' ),
			'not_found'                  => esc_html__( 'No star item tags found.',                 'rebalance' ),
			'items_list_navigation'      => esc_html__( 'Star Item tag list navigation',    'rebalance' ),
			'items_list'                 => esc_html__( 'Star Item tag list',               'rebalance' ),
			'back_to_items'         	 => esc_html__( '&larr; Back to Star Item tags' ,  'rebalance' ),
		),
		'public'            => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'star-tag' ),
	) );
	
	
	register_taxonomy( 'constellation-cat', 'constellation', array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'                  => esc_html__( 'Constellation Categories',                 'rebalance' ),
			'singular_name'         => esc_html__( 'Constellation Category',                  'rebalance' ),
			'menu_name'             => esc_html__( 'Constellation Categories',                 'rebalance' ),
			'all_items'             => esc_html__( 'All Categories',             'rebalance' ),
			'edit_item'             => esc_html__( 'Edit Constellation Category',             'rebalance' ),
			'view_item'             => esc_html__( 'View Constellation Category',             'rebalance' ),
			'update_item'           => esc_html__( 'Update Constellation Category',           'rebalance' ),
			'add_new_item'          => esc_html__( 'Add New Constellation Category',          'rebalance' ),
			'new_item_name'         => esc_html__( 'New Constellation Category Name',         'rebalance' ),
			'parent_item'           => esc_html__( 'Parent Constellation Category',           'rebalance' ),
			'parent_item_colon'     => esc_html__( 'Parent Constellation Category:',          'rebalance' ),
			'search_items'          => esc_html__( 'Search Constellation Categories',          'rebalance' ),
			'items_list_navigation' => esc_html__( 'Constellation category list navigation',  'rebalance' ),
			'items_list'            => esc_html__( 'Constellation category list',             'rebalance' ),
			'back_to_items'         => esc_html__( '&larr; Back to Constellation Categories' ,  'rebalance' ),
		),
		'public'            => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'constellation-cat' ),
	) );
	
	
	register_taxonomy( 'constellation-tag', 'constellation', array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'                       => esc_html__( 'Constellation Tags',                   'rebalance' ),
			'singular_name'              => esc_html__( 'Constellation Tag',                    'rebalance' ),
			'menu_name'                  => esc_html__( 'Constellation Tags',                   'rebalance' ),
			'all_items'                  => esc_html__( 'All Constellation Tags',               'rebalance' ),
			'edit_item'                  => esc_html__( 'Edit Constellation Tag',               'rebalance' ),
			'view_item'                  => esc_html__( 'View Constellation Tag',               'rebalance' ),
			'update_item'                => esc_html__( 'Update Constellation Tag',             'rebalance' ),
			'add_new_item'               => esc_html__( 'Add New Constellation Tag',            'rebalance' ),
			'new_item_name'              => esc_html__( 'New Constellation Tag Name',           'rebalance' ),
			'search_items'               => esc_html__( 'Search Constellation Tags',            'rebalance' ),
			'popular_items'              => esc_html__( 'Popular Constellation Tags',           'rebalance' ),
			'separate_items_with_commas' => esc_html__( 'Separate constellation tags with commas',      'rebalance' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove constellation tags',             'rebalance' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used constellation tags', 'rebalance' ),
			'not_found'                  => esc_html__( 'No constellation tags found.',                 'rebalance' ),
			'items_list_navigation'      => esc_html__( 'Constellation tag list navigation',    'rebalance' ),
			'items_list'                 => esc_html__( 'Constellation tag list',               'rebalance' ),
			'back_to_items'         	 => esc_html__( '&larr; Back to Constellation tags' ,  'rebalance' ),
		),
		'public'            => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'constellation-tag' ),
	) );	
}

// customize the admin messages
function constellection_updated_star_messages( $messages ) {
	global $post;

	$messages['star-item'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => sprintf( __( 'Star Item updated. <a href="%s">View item</a>', 'rebalance'), esc_url( get_permalink( $post->ID ) ) ),
		2  => esc_html__( 'Custom field updated.', 'rebalance' ),
		3  => esc_html__( 'Custom field deleted.', 'rebalance' ),
		4  => esc_html__( 'Star Item updated.', 'rebalance' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( esc_html__( 'Star Item restored to revision from %s', 'rebalance'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => sprintf( __( 'Star Item published. <a href="%s">View star item</a>', 'rebalance' ), esc_url( get_permalink( $post->ID ) ) ),
		7  => esc_html__( 'Star Item saved.', 'rebalance' ),
		8  => sprintf( __( 'Star Item submitted. <a target="_blank" href="%s">Preview star item</a>', 'rebalance'), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) ),
		9  => sprintf( __( 'Star Item scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview project</a>', 'rebalance' ),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i', 'rebalance' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post->ID ) ) ),
		10 => sprintf( __( 'Star Item item draft updated. <a target="_blank" href="%s">Preview star item</a>', 'rebalance' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) ),
	);

	return $messages;
}

// customize the admin messages for constellations
function constellection_updated_constellation_messages( $messages ) {
	global $post;

	$messages['constellation'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => sprintf( __( 'Constellation updated. <a href="%s">View item</a>', 'rebalance'), esc_url( get_permalink( $post->ID ) ) ),
		2  => esc_html__( 'Custom field updated.', 'rebalance' ),
		3  => esc_html__( 'Custom field deleted.', 'rebalance' ),
		4  => esc_html__( 'Constellation updated.', 'rebalance' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( esc_html__( 'Constellation restored to revision from %s', 'rebalance'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => sprintf( __( 'Constellation published. <a href="%s">View constellation</a>', 'rebalance' ), esc_url( get_permalink( $post->ID ) ) ),
		7  => esc_html__( 'Constellation saved.', 'rebalance' ),
		8  => sprintf( __( 'Constellation submitted. <a target="_blank" href="%s">Preview constellation</a>', 'rebalance'), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) ),
		9  => sprintf( __( 'Constellation scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview project</a>', 'rebalance' ),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i', 'rebalance' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post->ID ) ) ),
		10 => sprintf( __( 'Constellation item draft updated. <a target="_blank" href="%s">Preview constellation</a>', 'rebalance' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) ),
	);

	return $messages;
}
	
// on theme activation make sure permalinks work
function constellection_flush_rules_on_switch() {
	flush_rewrite_rules();
}	

// modify the admin columns for star items
function constellection_star_admin_columns( $columns ) {
		// change 'Title' to 'Artifact'
		$columns['title'] = __( 'Star Item', 'rebalance' );

		return $columns;
}

// modify the admin columns for constellations
function constellection_constellation_admin_columns( $columns ) {
		// change 'Title' to 'Artifact'
		$columns['title'] = __( 'Constellation', 'rebalance' );

		return $columns;
}



# -----------------------------------------------------------------
# meta data for posts and content types
# -----------------------------------------------------------------

function constellection_entry_meta() {

	global $post;

	// Get time string
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	// Format time string
	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	// Output variable
	$entry_meta_output = '';
	// Author
	$author = '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';
	// Post date
	$post_date = 'Published: <span class="entry-tags-date"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a></span>';
	// Categories
	$categories_list = get_the_term_list( get_the_ID(), 'category', '<span class="entry-categories">', esc_html_x( ', ', 'Categories separator', 'rebalance' ), '</span>' );
	
	
	if ( 'star-item' === get_post_type() ) {
	
		// credut the contributor
		$entry_meta_output .= 'Shared by: <span class="author vcard"><strong>' . $post->star_maker . '</strong></span>';
		
		// Star type
		$entry_meta_output .= $post_date . '<br />';
		
		$entry_meta_output .=  'Star ID: <span class="entry-categories"><strong>' . get_the_ID() . '</strong></span> ';
		
		$entry_meta_output .=   'Type: ' . get_the_term_list( get_the_ID(), 'star-type', '<span class="entry-categories">', esc_html_x( ', ', 'Categories separator', 'rebalance' ), '</span>' );
		
		$entry_meta_output .=    'Medium: ' . get_the_term_list( get_the_ID(), 'star-media', '<span class="entry-categories">', esc_html_x( ', ', 'Categories separator', 'rebalance' ), '</span>' ) . '<br />';
		
		$views = $post->star_views;
		$views++;
		$entry_meta_output .=  'Views: <span class="entry-categories"><strong>' . $views . '</strong></span> ';
		
		//bump the view count
		update_post_meta( $post->ID,  'star_views', $views );
		
		$entry_meta_output .=  'Estimated Time: <span class="entry-categories"><strong>' . constellection_get_time_estimates( $post->star_time ) . '</strong></span> ';

	} elseif ( 'constellation' === get_post_type() ) {	
	
		$entry_meta_output .= 'Created by: <span class="author vcard"><strong>' . $post->constellation_maker . '</strong></span>';

	
		$entry_meta_output .= $post_date  . '<br />';
		$entry_meta_output .=   'Category: ' . get_the_term_list( get_the_ID(), 'constellation-cat', '<span class="entry-categories">', esc_html_x( ', ', 'Categories separator', 'rebalance' ), '</span>' );
		
		$views = $post->star_views;
		$views++;
		$entry_meta_output .=  'Views: <span class="entry-categories"><strong>' . $views . '</strong></span> ';

		//bump the view count
		update_post_meta( $post->ID,  'star_views', $views );
		
		
		
	} else {
		// normal post
		$entry_meta_output .= $author;
		$entry_meta_output .= $post_date;
		$entry_meta_output .= $categories_list;

	}
	
	echo $entry_meta_output; 
	
	// Edit link
	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'rebalance' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}


function constellection_entry_footer() {

	global $post;
	
	// Posts
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$tags_list = 'Tagged: ' . get_the_tag_list( '', esc_html__( ', ', 'rebalance' ) );
		if ( $tags_list && ! is_wp_error( $tags_list ) ) {
			echo '<span class="entry-tags">' . $tags_list . '</span>';
		}
	}

	// Star Items
	if ( is_single() && 'star-item' === get_post_type() ) {
		// Show project TAGS on single star type templates
		
		$star_footer =  'Tagged: ' . get_the_term_list( get_the_ID(), 'star-tag', '<span class="entry-tags">', esc_html__( ', ', 'rebalance' ), '</span>' );
		
		$star_footer .= '<br />Access: <span class="entry-other">'  . $post->star_access . '</span>';
		
		$star_footer .= '<br />URL: <span class="entry-categories"><a href="' . $post->star_link . '" target="_blank">' . $post->star_link . '</span></a>';
		
		$star_footer .= '<p>This star is used in the following constellations:</p>' .  get_constellations_for_star($post->in_constellations);
		
		echo $star_footer;

	} elseif ( 'star-item' === get_post_type() ) {
		// Show project TYPES on all other project templates
		$star_type_list = get_the_term_list( get_the_ID(), 'star-item', '<span class="entry-categories">', esc_html__( ', ', 'rebalance' ), '</span>' );
		if ( $star_type_list ) {
			echo $star_type_list;
		}
	}
	
	// Cosntellations
	if ( is_single() && 'constellation' === get_post_type() ) {
		// Show project TAGS on single star type templates
		
		$c_footer =  '<p>Tagged: ' . get_the_term_list( get_the_ID(), 'constellation-tag', '<span class="entry-tags">', esc_html__( ', ', 'rebalance' ), '</span>' );
		
		$c_footer = '</p><p>Stars in this constellation:</p>' . get_stars_for_constellation( $post->star_list );
		
		
		echo $c_footer;

	} elseif ( 'constellation' === get_post_type() ) {
		// Show project TYPES on all other project templates
		$star_type_list = get_the_term_list( get_the_ID(), 'constellation', '<span class="entry-categories">', esc_html__( ', ', 'rebalance' ), '</span>' );
		if ( $star_type_list ) {
			echo $star_type_list;
		}
	}

	

	// Edit link
	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'rebalance' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}

function constellection_card_footer () {
		global $post;
		
		$c_footer = '';
		
		$c_footer .=   'In: ' . get_the_term_list( get_the_ID(), 'constellation-cat', '<span class="entry-categories">', esc_html_x( ', ', 'Categories separator', 'rebalance' ), '</span>' );

		$c_footer .=  'Views: <span class="entry-categories"><strong>' . $post->star_views . '</strong></span> ';
		
		echo $c_footer;

}

# -----------------------------------------------------------------
# Constellation Tools
# -----------------------------------------------------------------

function constellection_update_json_data( $post_id, $post, $update ) {

    /*
     * In production code, $slug should be set only once in the plugin,
     * preferably as a class property, rather than in each function that needs it.
     */
    $post_type = get_post_type($post_id);

    // If this isn't a 'constellation' post, don't update it.
    if ( "constellation" != $post_type ) return;

	if ($post->$star_list) {
    
		// get the star data
		$sdata = constellection_get_star_data( $post->star_list );
	
		// get some json for the nodes
		$ndata = constellection_make_star_node( $sdata );
	
		$ldata = constellection_make_star_link( $sdata);
	
		// put the node data into post_meta	
		update_post_meta( $post_id, 'nodejson',  wp_slash($ndata));
		update_post_meta( $post_id, 'linkjson',  $ldata );
   }
}

add_action( 'save_post', 'constellection_update_json_data', 10, 3 );


function constellection_get_star_data( $star_list = ''  ) {

	// exit if we have no stars
	if ( $star_list == '' ) return [];
	
	
	// query for get_posts
	$args = array(
  		'post_type'   => 'star-item',
  		'include'	  => explode(',', $star_list)
  	);
  	
  	// get info on all stars, get it? 
  	$all_stars  = get_posts( $args );
  	
  	if ( $all_stars ) {
  		$star_data = []; // data holder
  		
		foreach ( $all_stars as $star ) {
		
		
			// convert string of ids  constellations to an array
			$in_c = explode(",", $star->in_constellations);
			
			$rating = ( empty($star->ratings_score ) ) ? 0 : (int)$star->ratings_score;
			
			// get ids for all taxonomy items
			$star_tags = wp_get_post_terms( $star->ID, 'star-tag', array( 'fields' => 'ids' ) );
			$star_types = wp_get_post_terms( $star->ID, 'star-type', array( 'fields' => 'ids' ) );
			$star_media = wp_get_post_terms( $star->ID, 'star-media', array( 'fields' => 'ids' ) );
			
			//load data for id, ratings raw score, view count in array element
			$star_data[] = [ 'id' => $star->ID, 'in_c' => count($in_c), 'rating' => $rating, 'views' => (int)$star->star_views, 'times' => (int)$star->star_time, 'tags' => $star_tags, 'types' => $star_types, 'media' => $star_media];
		}
		
		return $star_data;
	} else {
		return [];
	}
}

function constellection_make_star_node( 
	$star_data = [], 
	$size_max = 20, 
	$size_min = 4,
	$weighting = ['views' => 0.5, 'in_c' => 0.3, 'rating' => 0.2] ) {


	// init the node data
	$node_data = []; 
	
	if (!$star_data) {
		// error, no data
		$node_data[] = ['error' => 'Sad, this part of the universe has no star data.'];
		
	} else {
	
	 	// let's do some math!
	 	
		// initialize sums
		$sums = ['views' => 0, 'in_c' => 0, 'rating' => 0];
	
		// let's get totals for parameters
		foreach ($star_data as $star) {
				// bump totals
				$sums['views']+= $star['views'];
				$sums['in_c']+= $star['in_c'];
				$sums['rating']+= $star['rating'];
		}

		foreach ($star_data as $star) {

			// calculate the weighted node size
			$star_size = (int)(( $star['views'] 	/ $sums['views'] * $weighting['views'] +
					$star['in_c'] 	/ $sums['in_c'] * $weighting['in_c'] +
					$star['rating'] / $sums['rating'] * $weighting['rating'] )
					* $size_max);
	
			$node_data[] = [
				'id' => $star['id'], 
				'title' => get_the_title( $star['id'] ) , 
				'size' => max( $size_min, $star_size ), 
				'url' =>  get_the_permalink( $star['id'] ) 
				];
		}
	}
	
	return json_encode( $node_data );

}

function constellection_make_star_link( 
	$star_data = [], 
	$dist_max = 240, 
	$dist_min = 80,
	$extra_links = 3,
	$line_value = 8
	) {
	
	
	// init the link data
	$link_data = []; 
	
	if (!$star_data) {
		// error, no data
		$link_data[] = ['error' => 'Sad, this part of the universe has no star data.'];
		
	} else {
	
	 	// mix it up
	 	
	 	shuffle_assoc($star_data);
	 	
	 	
	
		// walk the stars
		for  ( $k=0; $k < count($star_data); $k++) {
			
			// empty the linked list for this star
			$linked_to = [];
			
			$source_id = $star_data[$k]['id'];
			
			// no self linking
			$linked_to[] = $source_id;
			
			// link to next star in array if not at end
			
			if ( $k < count($star_data)-1 ) {
				$target_id = $star_data[$k+1]['id'];
				$linked_to[] = $target_id ;
			
				// add first connection
				$link_data[] = ['source' => $source_id, 'target' => $target_id , 'value' => $line_value, 'dist' => constellection_get_line_distance($star_data[$k], $star_data[$k+1], $dist_max, $dist_min) ];
				
			}
			
			
			// now add on some additional tries
			
			$iterations = rand(1, $extra_links ); 
		
			// connect at least 1 but no more than $extra_links stars
			for ( $i=1; $i< $iterations; $i++) {
				
				$done = false;
				$cnt = 0;
				
				while (!$done and $cnt < 1000) {
				
					$cnt++;
					$randy = rand( 0, count( $star_data )-1 );
					
					$target_id =  $star_data[$randy]['id'];
					
					if ( !in_array ( $target_id,  $linked_to) ) {
						$done = true;
						$linked_to[] = $target_id;
						$link_data[] = ['source' => $source_id, 'target' => $target_id, 'value' => $line_value, 'dist' => constellection_get_line_distance($star_data[$k], $star_data[$randy], $dist_max, $dist_min) ];
					}
						
				
				}
			}
		}
	}
	
	return json_encode( $link_data );
	
}

function constellection_get_line_distance( $source, $target, $dist_max, $dist_min) {


	$times_diff =  abs( $source['times'] - $target['times']);
	
	if ( is_array( $source['tags'] ) and is_array( $target['tags'] ) ) {
		$tag_commons = count( array_intersect($source['tags'], $target['tags']) );
	} else {
		$tag_commons = 0;
	}

	if (is_array( $source['types'] ) and is_array( $target['types'] ) ) {
		$type_common = count( array_intersect($source['types'], $target['types']) );
	} else {
		$type_common = 0;
	}	

	if (is_array( $source['media'] ) and is_array( $target['media'] ) ) {
		$media_common = count( array_intersect($source['media'], $target['media']) );
	} else {
		$media_common = 0;
	}	
	
	$line_distance =  $dist_max - ( (300 * $tag_commons) + (90 * $type_common) + (10 * $media_common) + 2 * (10 - $times_diff + rand(0,50) ) ) / 2;
	
	return ( max( $dist_min, $line_distance) );

	
}

# -----------------------------------------------------------------
# Contellation Making
# -----------------------------------------------------------------


function constellection_json_exists () {
		global $post;
			
		return ($post->linkjson AND $post->nodejson);

}

/**
 * Simple Templating function
 *
 * h/t https://www.daggerhart.com/create-simple-php-templating-function/
 * @param $file   - Path to the PHP file that acts as a template.
 * @param $args   - Associative array of variables to pass to the template file.
 * @return string - Output of the template file. Likely HTML.
 */
 
 
function constellection_template( $file, $args ) {

  // ensure the file exists
	if ( !file_exists( $file ) ) {
		return 'Output template missing: ' . $file;
	}

  // Make values in the associative array easier to access by extracting them
	if ( is_array( $args ) ) {
		extract( $args );
		
		// buffer the output (including the file is "output")
		ob_start();
		include $file;
		return ob_get_clean();
		
	} else {
	
		return 'Argument data missing or invalid array.';
	
	}


}

# -----------------------------------------------------------------
# Star Tools
# -----------------------------------------------------------------

function constellection_all_time_strings() {
	// return array for the codes thst match the amount of time needed to do an item
	
	return ( array (
				'n/a',
				'less than 5 minutes',
				'less than 30 minutes',
				'an hour or less',
				'1-3 hours',
				'4-8 hours',
				'1-2 days'
			)
		);
}


function constellection_get_time_estimates( $lcode=0 ) {
	// output full text for a time estimate code
	$all_time_estimates = constellection_all_time_strings();
	
	return $all_time_estimates[$lcode];
}

// return a list of all stars provided as comma separated string for var star_list 
function get_constellations_for_star( $constellation_list='') {

	if ( $constellation_list == '') {
		return '<p>This star has not appear yet in any constellations.</p>';
	}
	
	// make it an array
	$the_constellations = explode(",", $constellation_list);
	
	$output = '<ul>';
	foreach ($the_constellations as $constellation_id) {
		$output .= '<li>Constellation ID ' . $constellation_id . '- <a href="' . get_the_permalink($constellation_id) . '" target="_blank">' . get_the_title($constellation_id) . '</a></li>';
	}
	
	$output .= '</ul>';
	
	return ($output);
}

function get_stars_for_constellation( $star_list='') {

	if ( $star_list == '') {
		return '<p>Sadly this constellation has no stars!</p>';
	}
	
	// make it an array
	$the_stars = explode(",", $star_list);
	
	$output = '<ul>';
	foreach ($the_stars as $star_id) {
		$output .= '<li>Star ID ' . $star_id . '- <a href="' . get_the_permalink($star_id) . '" target="_blank">' . get_the_title($star_id) . '</a></li>';
	}
	
	$output .= '</ul>';
	
	return ($output);

}

# -----------------------------------------------------------------
# Display Stuff
# -----------------------------------------------------------------

# -----------------------------------------------------------------
# Shortcodes
# -----------------------------------------------------------------

// ----- short code for number of assignments in the bank
add_shortcode('star_count', 'constellection_count_the_stars');

function constellection_count_the_stars( $atts ) {

	extract( shortcode_atts( array( "link" => 0), $atts ) );  
	
	if ( $link ) {
		return '<a href="' . get_post_type_archive_link( 'star-item' ) . '">' . wp_count_posts('star-item')->publish  . ' star items</a>';
	} else {
		return wp_count_posts('star-item')->publish  . ' star items';
	}
}

// ----- short code for number of examples in the bank
add_shortcode('constellation_count', 'constellection_count_the_sky');

function constellection_count_the_sky( $atts ) {

	extract( shortcode_atts( array( "link" => 0), $atts ) );  
	
	if ( $link ) {
		return '<a href="' . get_post_type_archive_link( 'constellation' ) . '">' . wp_count_posts('constellation')->publish  . ' constellations</a>';
	} else {
		return wp_count_posts('constellation')->publish . ' constellations';
	}
}


# -----------------------------------------------------------------
# Admin stuff
# -----------------------------------------------------------------

/* stop jetpack nags 
	h/t https://gist.github.com/digisavvy/174a8a65accce24d9bc8c8f2441e9bdb     */
	
function constellection_admin_theme_style() {

	wp_register_style( 'custom_wp_admin_css', get_stylesheet_directory_uri() . '/style-admin.css', false, '1.0.0' );
    wp_enqueue_style( 'custom_wp_admin_css' );

}

add_action('admin_enqueue_scripts', 'constellection_admin_theme_style');




function shuffle_assoc(&$array) {
	// h/t http://us1.php.net/manual/en/function.shuffle.php
	$keys = array_keys($array);

	shuffle($keys);

	foreach($keys as $key) {
		$new[$key] = $array[$key];
	}

	$array = $new;

	return true;
}

/**
 * Recursively sort an array of taxonomy terms hierarchically. Child categories will be
 * placed under a 'children' member of their parent term.
 * @param Array   $cats     taxonomy term objects to sort
 * @param Array   $into     result array to put them in
 * @param integer $parentId the current parent ID to put them in
   h/t http://wordpress.stackexchange.com/a/99516/14945
 */
function constellection_sort_terms_hierarchicaly(Array &$cats, Array &$into, $parentId = 0)
{
    foreach ($cats as $i => $cat) {
        if ($cat->parent == $parentId) {
            $into[$cat->term_id] = $cat;
            unset($cats[$i]);
        }
    }

    foreach ($into as $topCat) {
        $topCat->children = array();
        bank106_sort_terms_hierarchicaly($cats, $topCat->children, $topCat->term_id);
    }
}

function cleanTags( $str ) {
	// replace multiple white spaces in tags to single blanks
	$cleansed = preg_replace('!\s+!', ' ', $str);
	// now convert blanks to commas
	$cleansed = str_replace ( ' ', ',' , $cleansed );
	
	$cleansed = preg_replace('!,+!', ',', $cleansed);

	// return the cleaned string
	return ($cleansed);

}

// -----  add allowable url parameters
add_filter('query_vars', 'constellection_queryvars' );

function constellection_queryvars( $qvars ) {
	$qvars[] = 'look'; // thing in the sky
	
	return $qvars;
}  

// -----  rewrite rules for licensed pretty urls
add_action('init', 'constellection_rewrite_rules', 10, 0); 
      
function constellection_rewrite_rules() {
	$random_page = get_page_by_path('random');
	
	
	if ( $random_page ) {
		add_rewrite_rule( '^random/([^/]*)/?',  'index.php?page_id=' . $random_page->ID . '&look=$matches[1]', 'top');	
	}	
}




// remove the page template placed there for jetpack portfolios
// h/t https://gist.github.com/rianrietveld/11245571

function constellection_remove_page_template( $pages_templates ) {
    unset( $pages_templates['portfolio-page.php'] );
return $pages_templates;
}

add_filter( 'theme_page_templates', 'constellection_remove_page_template' );



?>