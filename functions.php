<?php

// Put this code -> show gist github
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles');

function my_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

class Gists_oEmbed {
	public function __construct(){
		add_action( 'init', array( $this, 'setup_handler' ) );
	}
	public function gist_result( $url ){
		$url = $url[0];
		if( !preg_match( '#\.js$#i', $url ) )
			$url .= '.js';
		return '<script src="' . $url . '"></script>';
	}
	public function setup_handler(){
		wp_embed_register_handler( 'gist', '#https?://gist.github.com/.*#i', array( $this, 'gist_result' ) );
	}
}
$gists_oembed = new Gists_oEmbed();

// Put this code -> show featured image
add_action('rest_api_init', 'register_rest_images' );
function register_rest_images(){
    register_rest_field( array('post'),
        'featured_img_url',
        array(
            'get_callback'    => 'get_rest_featured_image',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}
function get_rest_featured_image( $object, $field_name, $request ) {
    if( $object['featured_media'] ){
        $img = wp_get_attachment_image_src( $object['featured_media'], 'app-thumb' );
        return $img[0];
    }
    return false;
}