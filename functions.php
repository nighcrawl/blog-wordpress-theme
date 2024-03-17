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
			'aside',
            'gallery',
			'photo',
            'video',
        )
    );
}
add_action('after_setup_theme', 'actheme_post_formats', 11);

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

function rss_post_thumbnail($content) {
	global $post;
	if(has_post_thumbnail($post->ID)) {
		$content = '<p>' . get_the_post_thumbnail($post->ID) . '</p>' . get_the_content();
	}
	return $content;
}
add_filter('the_excerpt_rss', 'rss_post_thumbnail');
add_filter('the_content_feed', 'rss_post_thumbnail');

function generate_post_title($data) {
  if(empty($data['post_title'])) {

    $content = strip_tags($data['post_content']);
    $first_part = smart_trim($content, 10);

    $data['post_title'] = $first_part;
    $data['post_name'] = sanitize_title($first_part);
    
  }
  return $data; // Returns the modified data.
}
add_filter( 'wp_insert_post_data' , 'generate_post_title' , '99', 1 ); 

function smart_trim($string, $truncation = 6) {
  // Utilisation de l'expression régulière pour détecter la première phrase
  // On recherche la première occurrence d'un point, d'un point d'exclamation ou d'un point d'interrogation suivis d'un espace ou de la fin de la chaîne
  $pattern = '/^((?:\S+\s+){0,'.($truncation - 1).'}(?:\S+))(?:[.!?]|$)/';

  // Exécution de l'expression régulière sur la chaîne de caractères
  preg_match($pattern, $string, $matches);
  
  // Si une correspondance est trouvée
  if (!empty($matches)) {
      // Si la phrase contient plus de mots que le nombre spécifié, on retourne les premiers mots suivis de points de suspension
      $words = explode(' ', $matches[1]);
      if (count($words) > $truncation) {
          return implode(' ', array_slice($words, 0, $truncation)) . '...';
      } else {
          // Sinon, on retourne toute la phrase
          return $matches[0];
      }
  } else {
      // Si aucun point n'est trouvé, on tronque la chaîne au nombre de mots spécifié
      $words = explode(' ', $string);
      return implode(' ', array_slice($words, 0, $truncation)) . '...';
  }
}