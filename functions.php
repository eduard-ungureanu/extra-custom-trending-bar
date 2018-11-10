<?php
/* #01 Load Parent Theme style.css file
=============================== */
function extra_enqueue_styles() {
	wp_enqueue_style( 'extra-parent', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'extra_enqueue_styles' );

/*================================================
#Load the translations from the child theme folder
================================================*/
function wpcninja_translation() {
	load_child_theme_textdomain( 'extra', get_stylesheet_directory() . '/lang/theme/' );
	load_child_theme_textdomain( 'et_builder', get_stylesheet_directory() . '/lang/builder/' );

}
add_action( 'after_setup_theme', 'wpcninja_translation' );


/*Custom function for customizing the Trending Bar*/
function wpc_extra_get_header_vars() {
	$items = array();

	$header_items = array(
		'header_social_icons',
		'header_search_field',
		'header_cart_total',
	);

	foreach ( $header_items as $header_item ) {
		$items['show_' . $header_item ] = extra_customizer_el_visible( extra_get_dynamic_selector( $header_item ) );
		$items['output_' . $header_item] = $items['show_' . $header_item ] || is_customize_preview();
	}

	$items['show_header_trending_bar'] = et_get_option( 'show_header_trending', 'on' );
	$items['output_header_trending_bar'] = $items['show_header_trending_bar'] || is_customize_preview();

	$items['header_search_field_alone'] = false;

	$items['header_cart_total_alone'] = false;

	$items['secondary_nav'] = wp_nav_menu( array(
		'theme_location' => 'secondary-menu',
		'container'      => '',
		'fallback_cb'    => '',
		'menu_class'     => 'nav',
		'menu_id'        => 'et-secondary-menu',
		'echo'           => false,
	) );

	$trending_posts = new WP_Query( apply_filters( 'extra_trending_posts_query', array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => '3',
		'cat' 			 => get_cat_ID('Video Posts'),
		'orderby'        => 'comment_count',
		'order'          => 'DESC',
	) ) );
	$items['trending_posts'] = isset( $trending_posts->posts ) ? $trending_posts : false;

	$items['top_info_defined'] = false;

	$top_info_items = array(
		'show_header_social_icons',
		'secondary_nav',
		'show_header_trending_bar',
		'show_header_search_field',
		'show_header_cart_total',
	);

	$top_info_items_count = 0;
	foreach ( $top_info_items as $top_info_item ) {
		if ( !empty( $items[ $top_info_item ] ) ) {
			$top_info_items_count++;
			$items['top_info_defined'] = true;
		}
	}

	if ( 1 == $top_info_items_count ) {
		if ( !empty( $items['show_header_search_field'] ) ) {
			$items['header_search_field_alone'] = true;
			$items['show_header_search_field'] = false;
		}

		if ( !empty( $items['show_header_cart_total'] ) ) {
			$items['header_cart_total_alone'] = true;
			$items['show_header_cart_total'] = false;
		}

		if ( $items['header_search_field_alone'] || $items['header_cart_total_alone'] ) {
			$items['top_info_defined'] = false;
			add_filter( 'wp_nav_menu_items', 'extra_primary_nav_extended_items', 10, 2 );
		}
	} elseif ( is_customize_preview() ) {
		add_filter( 'wp_nav_menu_items', 'extra_primary_nav_extended_items', 10, 2 );
	}

	$items['header_style'] = et_get_option( 'header_style', 'left-right' );

	$items['header_ad'] = extra_display_ad( 'header', false );

	$header_classes = array();

	if ( !empty( $items['header_style'] ) && 'centered' == $items['header_style'] ) {
		$header_classes[] = 'centered';
	} else {
		$header_classes[] = 'left-right';
	}

	if ( !empty( $header_ad ) ) {
		$header_classes[] = 'has_headerad';
	}

	$items['header_classes'] = extra_classes( $header_classes, 'header', false );

	return $items;
}