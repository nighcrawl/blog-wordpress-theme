<?php
function actheme_enqueue_styles() {
    $stylesheets = array(
        array(
            'handle' => 'parent-theme-style',
            'source' => get_template_directory_uri() . '/style.css',
            'deps' => array(),
            'version' => filemtime(get_template_directory_uri() . '/style.css'),
        ),
        array(
            'handle' => 'fork-awesome-style',
            'source' => get_stylesheet_directory_uri() . '/assets/css/fork-awesome.min.css',
            'deps' => array(),
            'version' => filemtime(get_stylesheet_directory_uri() . '/assets/css/fork-awesome.min.css'),
        ),
        array(
            'handle' => 'fonts-style',
            'source' => get_stylesheet_directory_uri() . '/assets/css/fonts.css',
            'deps' => array('parent-theme-style', 'fork-awesome-style'),
            'version' => filemtime(get_stylesheet_directory_uri() . '/assets/css/fonts.css'),
        ),
        array(
            'handle' => 'child-theme-style',
            'source' => get_stylesheet_directory_uri() . '/style.css',
            'deps' => array('parent-theme-style', 'fork-awesome-style', 'fonts-style'),
            'version' => filemtime(get_stylesheet_directory_uri() . '/style.css'),
        ),
    );

    foreach($stylesheets as $stylesheet) {
        wp_enqueue_style( $stylesheet['handle'], $stylesheet['source'], $stylesheet['deps'], $stylesheet['version'] );
    }

    wp_enqueue_script( 'codepenio-script', 'https://production-assets.codepen.io/assets/embed/ei.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'actheme_enqueue_styles' );

function actheme_post_formats() {    
    add_theme_support('post-formats',
        array(
            'link',
            'status',
            'gallery',
            'video',
        )
    );
}
add_action('after_setup_theme', 'actheme_post_formats', 11);


// Remove post formats from parent theme
function actheme_remove_parent_post_formats() {
    remove_post_type_support('post', 'post-formats');
}
add_action('init', 'actheme_remove_parent_post_formats', 11);

if ( ! function_exists( 'sempress_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 * Create your own sempress_posted_on to override in a child theme
	 *
	 * @since SemPress 1.0.0
	 */
	function sempress_posted_on() {
		printf( __( '<span class="sep">Publié le </span><a href="%1$s" title="%2$s" rel="bookmark" class="url u-url"><time class="entry-date updated published dt-updated dt-published" datetime="%3$s" itemprop="dateModified datePublished">%4$s</time></a><address class="byline"> <span class="sep"> par </span> <span class="author p-author vcard hcard h-card" itemprop="author " itemscope itemtype="http://schema.org/Person">%5$s <a class="url uid u-url u-uid fn p-name" href="%6$s" title="%7$s" rel="author" itemprop="url"><span itemprop="name">%8$s</span></a></span></address>', 'sempress' ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			get_avatar( get_the_author_meta( 'ID' ), 90 ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'sempress' ), get_the_author() ) ),
			esc_html( get_the_author() )
		);
	}
endif;