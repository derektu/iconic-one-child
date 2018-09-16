<?php
/**
 * Created by PhpStorm.
 * User: derektu
 * Date: 4/17/15
 * Time: 4:23 PM
 */

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
}

function custom_excerpt_length( $length ) {
    return 150;
}

add_filter( 'excerpt_length', 'custom_excerpt_length' );


