<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

$photo_gallery = get_post_meta( get_the_ID(), 'gallery_data', true );
foreach ($photo_gallery['image_url'] as $photo) {
    echo $photo;
}