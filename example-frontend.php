<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

$photo_gallery = get_post_meta( get_the_ID(), 'gallery_data', true );
if(!$photo_gallery){
    $photo_gallery = array();
    $photo_gallery['image_url'] = [];
}

foreach ($photo_gallery['image_url'] as $photo) {
    echo $photo;
}