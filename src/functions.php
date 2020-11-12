<?php

use Ramsey\Uuid\Uuid;

/**
 * Handle upload image
 *
 * @param array $uploadedFile single element of $_FILES
 * @param int $relationshipId Uploaded Image ID
 *
 * @return int|WP_Error Return ID on success or WP_Error on failure
 */
function handleUploadImageFile( $uploadedFile, $relationshipId = 0 ) {

	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	$uploadOverrides = array( 'test_form' => false );

	$file = wp_handle_upload( $uploadedFile, $uploadOverrides );

	if ( isset( $file['error'] ) ) {
		return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Не удалось обработать загрузку изображения.', $file['error'] );
	}

	$uuid     = Uuid::uuid4();
	$url      = $file['url'];
	$type     = $file['type'];
	$file     = $file['file'];
	$filename = 'image-' . $uuid->toString();

	$args = [
		'post_title'     => $filename,
		'post_name'      => $filename,
		'post_mime_type' => $type,
		'guid'           => $url
	];

	$id = wp_insert_attachment( $args, $file, $relationshipId, true );

	if ( ! is_wp_error( $id ) ) {

		wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );

	}

	return $id;
}