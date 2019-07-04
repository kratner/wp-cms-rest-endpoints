<?php
/**
 * Plugin Name: WordPress CMS Custom REST API Endpoints
 * Plugin URI: https://github.com/kratner/wp-post-featured-on-wpengine-blog
 * Description: Provides custom REST endpoints for CMS
 * Version: 1.0
 * Author: Keith Ratner
 * Author URI: http://keithratner.live
 */

function wpcmsapi_links($request) {
    $category_name = $request['name'];
    $args = Array(
        'post_type' => 'post',
        'posts_per_page' => '15',
        'category_name' => $category_name,
        'orderby' => 'date',
        'order' => 'DESC'
      );
    $query = new WP_Query($args);
    $posts = $query->get_posts();

    $controller = new WP_REST_Posts_Controller('post');

    $array = [];

    foreach ( $posts as $post ) {
        $data = $controller->prepare_item_for_response($post, $request);
        $array[] = $controller->prepare_response_for_collection($data);
    }

    return $array;
}
add_action( 'rest_api_init', function () {
        $trunk = 'wpcms/v1';
        register_rest_route( $trunk, '/posts-by-category/', array(
                'methods' => 'GET',
                'callback' => 'wpcmsapi_links'
            )
        );
    } 
);