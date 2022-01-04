<?php
/**
 * @wordpress-plugin
 * Plugin Name: CPT Gallery
 * Plugin URI: https://www.bthebrand.es
 * Description: Add gallery to Custom Post Types
 * Version: 1.0.0
 * Author: bthebrand
 * Author URI: https://www.bthebrand.es
 * License: GPL-2.0+
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.txt
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if(!defined('CPT_GALLERY_BASE_DIR')) {
    define('CPT_GALLERY_BASE_DIR', dirname(__FILE__));
}

if(!defined('CPT_GALLERY_TYPE')) {
    define('CPT_GALLERY_TYPE', 'villa');
}

function cpt_gallery_add_metabox(){
	add_meta_box(
		'post_custom_gallery',
		'Gallery',
		'cpt_gallery_metabox_callback',
		CPT_GALLERY_TYPE,
		'normal',
		'core'
	);
}
add_action( 'admin_init', 'cpt_gallery_add_metabox' );

function cpt_gallery_metabox_callback(){
	wp_nonce_field( basename(__FILE__), 'cpt_gallery_nonce' );
	global $post;
	$gallery_data = get_post_meta( $post->ID, 'gallery_data', true );
    echo '<div id="img_box_container">';
    if ( isset( $gallery_data['image_url'] ) ){
        foreach ($gallery_data['image_url'] as $key => $photo) {
            echo '<div class="gallery_single_row '.$key.'">';
            echo '<div class="image_container">';
            echo '<p>Drag to order</p>';
            echo '<img src="'.$photo.'" width="100"/>';
            echo '<input type="hidden" class="meta_image_url" name="gallery[image_url][]" value="'.$photo.'"/>';
            echo '</div>';
            echo '<span class="button" onclick="remove_img('.$key.')"/>Delete</span>';
            echo '<hr>';
            echo '</div>';
        }
    }
    echo '</div>';
    echo '<input class="button" type="button" value="Add New" onclick="open_media_uploader_image();"/>';
}

function cpt_gallery_scripts(){
    global $post;
    if( CPT_GALLERY_TYPE != $post->post_type )
        return;
    include(CPT_GALLERY_BASE_DIR . '/scripts.php');
}
add_action( 'admin_head-post.php', 'cpt_gallery_scripts' );
add_action( 'admin_head-post-new.php', 'cpt_gallery_scripts' );

function cpt_gallery_save( $post_id, $post ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'cpt_gallery_nonce' ] ) && wp_verify_nonce( $_POST[ 'cpt_gallery_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
    if ( $is_autosave || $is_revision || !$is_valid_nonce )
        return;
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return;
    if ( CPT_GALLERY_TYPE != $post->post_type )
        return;
    if ( isset($_POST['gallery']) ){
        $gallery_data = array();
        for ($i = 0; $i < count( $_POST['gallery']['image_url'] ); $i++ ){
            if ( '' != $_POST['gallery']['image_url'][$i]){
                $gallery_data['image_url'][]  = $_POST['gallery']['image_url'][ $i ];
            }
        }
        if ( $gallery_data ) {
            update_post_meta( $post_id, 'gallery_data', $gallery_data );
        } else {
            delete_post_meta( $post_id, 'gallery_data' );
        }
    }
}
add_action( 'save_post', 'cpt_gallery_save', 20, 2 );
