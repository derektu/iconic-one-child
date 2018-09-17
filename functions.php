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

function _echo_log( $what ) {
    echo '<pre>'.print_r( $what, true ).'</pre>';
}

function _output_log($log) {
    if (true === WP_DEBUG) {
        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }
}

function alter_category_query($query) {
    global $wp_query;

    if (is_admin())
        return;
    if (!is_category())        
        return;
    if (!$query->is_main_query())
        return;        
    $cat_id = get_query_var( 'cat' );
    if (empty($cat_id))
        return;
    $category = get_category($cat_id);
    if (empty($category) || $category->parent)
        return;

    _output_log('UPDATE CATEGORY QUERY !!');
            
    // only do this when we are in parent category query        
    $query->set('tax_query', array(
        'taxonomy' => 'category',
        'field'    => 'term_id',
        'terms'    => $cat_id,
    ));        
}
add_action('pre_get_posts', 'alter_category_query');

