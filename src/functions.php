<?php

use Ramsey\Uuid\Uuid;

/**
 * Handle upload image
 * Generate sizes and attach image to relation object by id
 *
 * @param array $uploadedFile single element of $_FILES
 * @param int $relationshipId Uploaded Image ID
 *
 * @return int|WP_Error Return ID on success or WP_Error on failure
 */
function handleUploadImageFile( $uploadedFile, $relationshipId = 0 ) {

	/*
	 * Require core lib
	 */
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	/*
	 * Disable test mode
	 */
	$uploadOverrides = array( 'test_form' => false );

	/*
	 * Upload File
	 */
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

	/*
	 * Insert an attachment
	 */
	$id = wp_insert_attachment( $args, $file, $relationshipId, true );

	/*
	 * Generate attachment meta data and create image sub-sizes for images
	 */
	if ( ! is_wp_error( $id ) ) {

		wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );

	}

	return $id;
}

/**
 * Create user profile
 * @param int $userId User ID
 * @param array $metaArgs Meta inputs args
 *
 * @return int|WP_Error Return Profile Id on success or WP_Error on failure
 */
function createProfile( $userId, $metaArgs ) {
	if (!userIsExists($userId)) {
		return new WP_Error(EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Неверный ID пользователя');
	}

	$errors = [];

	$requiredFields = [
		'ownerUserId',
		'firstName',
		'middleName',
		'lastName',
		'email',
		'phone',
		'education',
		'birthYear',
		'hourlyRate',
		'experience',
		'cityTermId',
		'placeTermIds',
		'subjectTermIds',
		'studentsTermIds',
	];

	$uuid = Uuid::uuid4();

	$defaultMetaArgs = [
		'uuid'            => $uuid->toString(),
		'ownerUserId'     => 0,
		'firstName'       => '',
		'middleName'      => '',
		'lastName'        => '',
		'email'           => '',
		'phone'           => '',
		'area'            => '',
		'education'       => '',
		'description'     => '',
		'birthYear'       => 0,
		'hourlyRate'      => 0,
		'experience'      => 0,
		'cityTermId'      => 0,
		'metroTermId'     => 0,
		'statusTermId'    => 0,
		'placeTermIds'    => [],
		'subjectTermIds'  => [],
		'studentsTermIds' => [],
		'markTermIds'     => [],
	];

	$args = wp_parse_args( $metaArgs, $defaultMetaArgs );

	/*
	 * Check required fields
	 */
	foreach ($requiredFields as $fieldName) {
		if (empty($args[$fieldName])) {
			$errors[$fieldName] = $fieldName . ' is required';
		}
	}

	/*
	 * if errors return WP_Error
	 */
	if (!empty($errors)) {
		return new WP_Error(EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Отсутсвуют следующие обязательные поля', $errors);
	}

	$defaultArgs = [
		'post_type'   => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
		'post_status' => 'pending',
	];

	return wp_insert_post( wp_parse_args( [ 'meta_input' => $args, 'post_author' => absint($userId) ], $defaultArgs ), true );
}

/**
 * Check user is exists
 * @param int|string $userId User ID
 *
 * @return bool
 */
function userIsExists($userId) {
	$userId = get_userdata(absint($userId));

	if (empty($userId) || is_wp_error($userId)) {
		return false;
	}

	return true;
}