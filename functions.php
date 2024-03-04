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